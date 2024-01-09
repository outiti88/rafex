<?php

namespace App\Http\Controllers;

use App\Facture;

use App\BonLivraison;
use App\Commande;
use App\Produit;
use App\User;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
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

        $clients = []; //tableau des clients existe dans la base de données
        $users = []; //les users qui seront affichés avec leur bon de livraison
        if (!Gate::denies('manage-users')) {
            $factures = DB::table('factures')->orderBy('created_at', 'DESC');
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->whereHas('commandes', function ($q) {
                $q->where('facturer', '0')->where('statut','Livré');
            })->orderBy('name')->get();
        } else {
            $factures = DB::table('factures')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC');
        }

        // $userTmp = User::withTrashed()->find($facture->user_id);
        //     $commandeNonFacrurer = $userTmp->whereHas('commandes', function ($q) {
        //             $q->where('facturer', '0')->where('commande','Livré');
        //         })->count();
        //     if($commandeNonFacrurer>0) $users[] = $userTmp;

        foreach ($factures->paginate(10) as $facture) {
            $users[] =  User::withTrashed()->find($facture->user_id);
        }
        $total = $factures->count();
        $factures = $factures->paginate(10);
        $data = null;
        return view('facture', [
            'nouveau' => $nouveau, 'factures' => $factures,
            'total' => $total,
            'users' => $users,
            'clients' => $clients, 'data' => $data
        ]);
    }

    public function filter(Request $request)
    {
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = []; //tableau des clients existe dans la base de données
        $users = []; //les users qui seront affichés avec leur bon de livraison
        if (!Gate::denies('manage-users')) {
            $factures = DB::table('factures')->orderBy('created_at', 'DESC');
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->orderBy('name')->get();
        } else {
            $factures = DB::table('factures')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC');
        }

        if ($request->filled('client')) {
            $factures->where('user_id', $request->client);
        }


        if ($request->filled('date_facture_min')) {
            $factures->whereDate('created_at', '>=', $request->date_facture_min);
        }
        if ($request->filled('date_facture_max')) {
            $factures->whereDate('created_at', '<=', $request->date_facture_max);
        }

        foreach ($factures->paginate(10) as $facture) {
            $users[] =  User::withTrashed()->find($facture->user_id);
        }
        $total = $factures->count();
        $factures = $factures->paginate(10);
        $data = $request->all();

        return view('facture', [
            'nouveau' => $nouveau, 'factures' => $factures,
            'total' => $total,
            'users' => $users,
            'clients' => $clients, 'data' => $data
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
        $user = $request->client;
        if (!Gate::denies('manage-users')) {
            $nbrCmdLivre =  DB::table('commandes')->where('statut', 'Livré')->where('user_id', $user)->where('facturer', '0')->count();
            $nbrCmdRamasse =  DB::table('commandes')->where('statut', 'En cours')->where('user_id', $user)->where('facturer', '0')->count();

            if ($nbrCmdLivre == 0) {

                $request->session()->flash('nbrCmdRamasse', $nbrCmdRamasse);
            } else {
                $facture = new Facture();
                $facture->numero = 'FAC_' . date("mdis");
                $facture->colis = DB::table('commandes')->where('user_id', $user)->whereIn('statut', ['Refusée'])->where('facturer', '0')->sum('colis');
                $facture->livre = $nbrCmdLivre;
                $fprixRefuser = DB::table('commandes')->where('user_id', $user)->where('statut', 'Refusée')->where('facturer', '0')->sum('refusePart');
                $facture->prix = DB::table('commandes')->where('user_id', $user)->where('statut', 'Livré')->where('facturer', '0')->sum('prix') + $fprixRefuser; //prix des commandes livrées

                $facture->montant = DB::table('commandes')->where('user_id', $user)->where('statut', 'Livré')->where('facturer', '0')->sum('montant');
                $facture->commande = DB::table('commandes')->where('user_id', $user)->whereIn('statut', ['Refusée'])->where('facturer', '0')->count(); //nbr de commanddes non livrée
                $facture->user()->associate($user)->save();
                $affected = DB::table('commandes')->where('user_id', $user)->whereIn('statut', ['Livré', 'Refusée'])->where('facturer', '=', '0')->update(array('facturer' => $facture->id));

                $request->session()->flash('ajoute');
            }
        } //rammsage-commande
        return redirect(route('facture.index'));
    } //fin fonction ajouter facture


    public function gen($id)
    {
        $facture = Facture::findOrFail($id);
        $user = DB::table('users')->find($facture->user_id);
        $livraisonNonPaye = 0;
        $prixLivrer = DB::table('commandes')->where('user_id', $user->id)->where('facturer', $facture->id)->where('statut', 'livré')->sum('prix');
        $prixRefuser =  DB::table('commandes')->where('user_id', $user->id)->where('facturer', $facture->id)->whereIn('statut', ['Retour en stock','Retour','Refusée'])->sum('refusePart');

        $commandes = DB::table('commandes')->where('user_id', $facture->user_id)->where('facturer', $facture->id)->get();

        $livraisonNonPaye += $prixRefuser + $prixLivrer;

        $net = $facture->montant - $livraisonNonPaye;

        $filename = 'Facture_' . $facture->numero;

        if ($user->id !== Auth::user()->id && Gate::denies('ramassage-commande')) {
            return redirect()->route('facture.index');
        }
        $commandesPerPages = $this->getCommandesPerPages($commandes);

        $pdf = App::make('dompdf.wrapper');
        $pdf = app('dompdf.wrapper')->loadView('pdf.facture', ['facture' => $facture, 'total' => count($commandes) , 'frais' => $livraisonNonPaye , 'commandesPerPages' => $commandesPerPages, 'filename' => $filename, 'net'=> $net])->setPaper('A4');


        return $pdf->stream($filename . 'pdf');
    }


    public function getCommandesPerPages($commandes)
    {
        $commandesPerPages = [];

        $m = ( count($commandes) / 12);
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


    public function search($id)
    {
        $data = null;
        $facture = Facture::findOrFail($id);
        $userId = $facture->user_id;
        $villes = DB::table('villes')->orderBy('name')->get();


        if ($userId !== Auth::user()->id && Gate::denies('ramassage-commande')) {
            return redirect()->route('facture.index');
        }
        //dd(Auth::user()->id );
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client', 'ecom']);
        })->orderBy('name')->get();
        $livreurs = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['livreur']);
        })->orderBy('name')->get();

        $users = [];
        $produits = [];

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

        if (!Gate::denies('manage-users')) {
            //session administrateur donc on affiche tous les commandes
            $total = Commande::where('deleted_at', NULL)->where('facturer', $id)->count();
            $commandes = Commande::where('deleted_at', NULL)->where('facturer', $id)->orderBy('created_at', 'DESC')->paginate(10);
            //dd($commandes);

            //dd($clients[0]->id);
        } else {
            $commandes = Commande::where('deleted_at', NULL)->where('facturer', $id)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
            $total = Commande::where('deleted_at', NULL)->where('facturer', $id)->where('user_id', Auth::user()->id)->count();

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
        $facture = Facture::findOrFail($id);
        $userId = $facture->user_id;
        $villes = DB::table('villes')->orderBy('name')->get();

        if ($userId !== Auth::user()->id && Gate::denies('ramassage-commande')) {
            return redirect()->route('facture.index');
        }
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $clients = [];
        $users = [];
        if (!Gate::denies('manage-users')) {
            $factures = DB::table('factures')->where('id', $id);
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->get();
        } else {
            $factures = DB::table('factures')->where('user_id', Auth::user()->id)->where('id', $id);
        }

        foreach ($factures->paginate(10) as $facture) {
            if (!empty(User::find($facture->user_id)))
                $users[] =  User::find($facture->user_id);
        }
        $total = $factures->count();
        $factures = $factures->paginate(10);
        $data = null;

        if ($total > 0) {
            //dd($factures);
            return view('facture', [
                'nouveau' => $nouveau, 'factures' => $factures,
                'total' => $total,
                'users' => $users,
                'clients' => $clients,
                'villes' => $villes, 'data' => $data
            ]);
        } else {
            return redirect()->route('facture.index');
        }
    }
}
