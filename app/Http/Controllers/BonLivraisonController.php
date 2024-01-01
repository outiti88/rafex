<?php

namespace App\Http\Controllers;

use App\BonLivraison;
use App\Commande;
use App\Produit;
use App\Ramassage;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BonLivraisonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $ramasse = DB::table('commandes')->where('user_id', Auth::user()->id)->where('statut', 'Reçue')->where('traiter', '0')->count();
        $nonRammase = DB::table('commandes')->where('user_id', Auth::user()->id)->where('statut', 'envoyée')->where('traiter', '0')->count();

        $clients = []; //tableau des clients existe dans la base de données
        $users = []; //les users qui seront affichés avec leur bon de livraison
        if (!Gate::denies('ramassage-commande')) {
            $bonLivraisons = DB::table('bon_livraisons')->orderBy('created_at', 'DESC');
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->get();
        } else {
            $bonLivraisons = DB::table('bon_livraisons')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC');
        }

        foreach ($bonLivraisons->paginate(10) as $bonLivraison) {
            if (!empty(User::withTrashed()->find($bonLivraison->user_id)))
                $users[] =  User::withTrashed()->find($bonLivraison->user_id);
        }

        $total = $bonLivraisons->count();
        $bonLivraisons = $bonLivraisons->paginate(10);
        $data = null;
        //dd($clients->get());
        return view('bonLivraison', [
            'nouveau' => $nouveau, 'bonLivraisons' => $bonLivraisons,
            'total' => $total,
            'users' => $users,
            'clients' => $clients,
            'ramasse' => $ramasse,
            'nonRamasse' => $nonRammase, 'data' => $data
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::denies('ramassage-commande')) {
            //dd($request->missing('price'));
            //admin
            $user = $request->client; //id de l'user choisi par l'admin
        } else {
            $user = Auth::user()->id; //session du client
        }
        $date = now();
        $cmdExist =  DB::table('commandes')->where('statut', 'Reçue')->where('traiter', '0')->where('user_id', $user)->count();

        // dd($cmdExist , $user);

        $bon_livraison = DB::table('bon_livraisons')->whereDate('created_at', now())->where('user_id', Auth::user()->id)->count();


        if (($cmdExist == 0)) { // 0 commande Reçue et jamais traiter
            $request->session()->flash('cmdExist');
        } else {
            $bonLivraison = new BonLivraison();
            $bonLivraison->colis = DB::table('commandes')->where('user_id', $user)->where('statut', 'Reçue')->where('traiter', '0')->sum('colis');
            $bonLivraison->commande = DB::table('commandes')->where('user_id', $user)->where('statut', 'Reçue')->where('traiter', '0')->count();
            $bonLivraison->prix = DB::table('commandes')->where('user_id', $user)->where('statut', 'Reçue')->where('traiter', '0')->sum('prix');
            $bonLivraison->montant = DB::table('commandes')->where('user_id', $user)->where('statut', 'Reçue')->where('traiter', '0')->sum('montant');
            $bonLivraison->nonRammase = 0;
            $bonLivraison->user()->associate($user)->save();

            $affected = DB::table('commandes')->where('user_id', $user)->where('statut', 'Reçue')->where('traiter', '=', '0')->update(array('traiter' => $bonLivraison->id));
            //dd($affected);

            $request->session()->flash('ajoute');
        }

        return redirect(route('bonlivraison.index'));
    }


    public function getCommandesPerPages(BonLivraison $bonLivraison)
    {
        $commandesPerPages = [];

        $commandes = DB::table('commandes')->where('traiter', $bonLivraison->id)->where('user_id', $bonLivraison->user_id)->get();

        $m = (($bonLivraison->nonRammase  + $bonLivraison->commande) / 12);
        $n = (int)$m; // nombre de page
        $n = ($n != $m) ? $n++ : $n;

        $pageNumber = 0;
        $numberOfCommandePerPage = 1;
        foreach ($commandes as  $commande) {
            if($numberOfCommandePerPage <= 8){
                $commandesPerPages[$pageNumber][] = $commande;
                $numberOfCommandePerPage++;
            }
            else{
                $pageNumber++;
                $commandesPerPages[$pageNumber][] = $commande;
                $numberOfCommandePerPage = 2;
            }
        }
        return $commandesPerPages;
    }


    public function gen($id)
    {
        $bonLivraison = BonLivraison::findOrFail($id);
        $user = DB::table('users')->find($bonLivraison->user_id);

        if ($bonLivraison->user_id !== Auth::user()->id && Gate::denies('ramassage-commande')) {
            return redirect()->route('bonlivraison.index');
        }

        $pdf = App::make('dompdf.wrapper');
        $ramassage = Ramassage::findOrFail($bonLivraison->ramassage_id);
        $filename = 'qr_code_' . $ramassage->reference . '.png';
        $commandesPerPages = $this->getCommandesPerPages($bonLivraison);
        $bonName = 'BL_'.bin2hex(substr($user->name, -strlen($user->name), 3)) . $bonLivraison->id ;
        $pdf = app('dompdf.wrapper')->loadView('pdf.ramassage', ['bonLivraison' => $bonLivraison, 'commandesPerPages' => $commandesPerPages, 'bonName' => $bonName, 'filename' => $filename])->setPaper('A4');

        return $pdf->stream('Bon_de_livraison_' . $bonName . '.pdf');
    }

    public function search($id)
    {
        //dd(Auth::user()->id );
        $data = null;
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client', 'ecom']);
        })->orderBy('name')->get();
        $produits = [];
        $livreurs = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['livreur']);
        })->orderBy('name')->get();
        $villes = DB::table('villes')->orderBy('name')->get();


        if (!Gate::denies('ecom')) {
            $produits_total = Produit::where('user_id', Auth::user()->id)->get();
            foreach ($produits_total as $produit) {
                $stock = DB::table('stocks')->where('produit_id', $produit->id)->get();
                if ($stock[0]->qte > 0) {
                    $produits[] = $produit;
                }
            }
            //dd($produits);
        }

        $users = [];
        if (!Gate::denies('manage-users')) {
            //session administrateur donc on affiche tous les commandes
            $total = Commande::where('deleted_at', NULL)->where('traiter', $id)->count();
            $commandes = Commande::where('deleted_at', NULL)->where('traiter', $id)->orderBy('created_at', 'DESC')->paginate(10);
            //dd($commandes);

            //dd($clients[0]->id);
        } else {
            $commandes = Commande::where('deleted_at', NULL)->where('traiter', $id)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
            $total = Commande::where('deleted_at', NULL)->where('traiter', $id)->where('user_id', Auth::user()->id)->count();

            //dd("salut");
        }


        foreach ($commandes as $commande) {
            if (!empty(User::withTrashed()->find($commande->user_id)))
                $users[] =  User::withTrashed()->find($commande->user_id);
        }
        //$commandes = Commande::all()->paginate(3) ;
        $statuts = [];
        $statutStat = [];

        if(!Gate::denies('manage-users')){
            $statuts = DB::table('commandes')
                ->select('statut', DB::raw('count(*) as total'))
                ->where('deleted_at', NULL)
                ->where(function ($q) {
                   $q->whereDate('updated_at', '>=', now()->subMonth())
                       ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
               })
                ->groupBy('statut')
                ->get();
        }
        else if(!Gate::denies('livreur')){
            $statuts = DB::table('commandes')
                ->select('statut', DB::raw('count(*) as total'))
                ->where('deleted_at', NULL)
                ->where('livreur', Auth::user()->id)
                ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue'])
                ->groupBy('statut')
                ->get();
        }else{
            $statuts = DB::table('commandes')
                ->select('statut', DB::raw('count(*) as total'))
                ->where('user_id', Auth::user()->id)
                ->where('deleted_at', NULL)
                ->where(function ($q) {
                   $q->whereDate('updated_at', '>=', now()->subMonth())
                       ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
               })
                ->groupBy('statut')
                ->get();
        }

        foreach ($statuts as $statut){
            $statutStat[$statut->statut] = $statut->total;
        }

        return view('commande.colis', [
            'commandes' => $commandes,
            'nouveau' => $nouveau,
            'total' => $total,
            'users' => $users,
            'clients' => $clients,
            'produits' => $produits,
            'livreurs' => $livreurs,
            'villes' => $villes,
            'data' => $data, 'checkBox' => null,
            'statutStat' => $statutStat

        ]);
    }


    public function infos($id)
    {
        $clients = [];
        $id_bon = $id;
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();
        $villes = DB::table('villes')->orderBy('name')->get();

        if (!Gate::denies('ramassage-commande')) {
            $bonLivraisons = DB::table('bon_livraisons')->where('id', $id_bon);
            //dd($bonLivraisons->count());
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->get();
        } else {
            $bonLivraisons = DB::table('bon_livraisons')->where('user_id', Auth::user()->id)->where('id', $id_bon);
        }


        foreach ($bonLivraisons->paginate(10) as $bonLivraison) {
            $users[] =  User::withTrashed()->find($bonLivraison->user_id);
        }
        $total = $bonLivraisons->count();
        $bonLivraisons = $bonLivraisons->paginate(10);
        $data = null;
        if ($total > 0) {
            $ramasse = DB::table('commandes')->where('user_id', Auth::user()->id)->where('statut', 'Reçue')->where('traiter', '0')->count();
            $nonRammase = DB::table('commandes')->where('user_id', Auth::user()->id)->where('statut', 'envoyée')->where('traiter', '0')->count();

            return view('bonLivraison', [
                'nouveau' => $nouveau, 'bonLivraisons' => $bonLivraisons,
                'total' => $total,
                'users' => $users,
                'clients' => $clients,
                'ramasse' => $ramasse,
                'nonRamasse' => $nonRammase,
                'villes' => $villes, 'data' => $data
            ]);
        } else {
            return redirect()->route('bonlivraison.index');
        }
    }
}
