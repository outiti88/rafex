<?php

namespace App\Http\Controllers;

use App\BonLivraison;
use App\Commande;
use App\CommandeProduit;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommande;
use App\Notifications\newCommande;
use App\Notifications\statutChange;
use App\Produit;
use App\Relance;
use App\Statut;
use App\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Nexmo\Laravel\Facade\Nexmo;
use App\Exports\CommandesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CommandesImport;
use App\Reclamation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class CommandeController extends Controller
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
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new CommandesExport, 'commandes.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);


        Excel::import(new CommandesImport, $request->file('select_file'));

        $request->session()->flash('excel');
        $dateTime = date('Ymd_His');
        $file = $request->file('select_file');
        $fileName = $dateTime . '-' . Auth::user()->name . '.xlsx';
        $savePath = public_path('/uploads/commandes/');
        $file->move($savePath, $fileName);

        return back()->with('success', 'Excel Data Imported successfully.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //dd(auth()->user()->unreadNotifications );
        //dd(Auth::user()->id );
        \Carbon\Carbon::setLocale('fr');
        $data = null;
        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client', 'ecom']);
        })->orderBy('name')->get();
        $livreurs = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['livreur']);
        })->orderBy('name')->get();
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $users = [];
        $produits = [];

        $statuts = [];
        $statutStat = [];

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

        if (!Gate::denies('manage-users')) {
            $produits_total = Produit::get();
            foreach ($produits_total as $produit) {
                $stock = DB::table('stocks')->where('produit_id', $produit->id)->get();
                if ($stock[0]->qte > 0) {
                    $produits[] = $produit;
                }
            }
            //session administrateur donc on affiche tous les commandes
            $total = Commande::where('deleted_at', NULL)
                ->where(function ($q) {
                    $q->whereDate('updated_at', '>=', now()->subMonth())
                        ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
                })
                ->count();
            $commandes = Commande::where('deleted_at', NULL)
                ->where(function ($q) {
                    $q->whereDate('updated_at', '>=', now()->subMonth())
                        ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
                })
                ->orderBy('updated_at', 'DESC')->paginate(50);
                $statuts = DB::table('commandes')
                ->select('statut', DB::raw('count(*) as total'))
                ->where('deleted_at', NULL)
                ->where(function ($q) {
                   $q->whereDate('updated_at', '>=', now()->subMonth())
                       ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
               })
                ->groupBy('statut')
                ->get();

            //dd($clients[0]->id);
        } elseif (!Gate::denies('livreur')) {
            //session livreur
            $statuts = DB::table('commandes')
                ->select('statut', DB::raw('count(*) as total'))
                ->where('deleted_at', NULL)
                ->where('livreur', Auth::user()->id)
                ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue','En attente de ramassage'])
                ->groupBy('statut')
                ->get();

            $livreurSession = Commande::where('deleted_at', NULL)->where('livreur', Auth::user()->id)
                ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue','En attente de ramassage'])
                ->orderBy('updated_at', 'DESC');
            $total = $livreurSession->get()->count();
            $commandes = $livreurSession->paginate(50);



            //dd($clients[0]->id);
        } else {
            $clientSession = Commande::where('deleted_at', NULL)->where('user_id', Auth::user()->id)
            ->where(function ($q) {
                $q->whereDate('updated_at', '>=', now()->subMonth())
                    ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
            });
            $total = $clientSession->count();

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

            $commandes = $clientSession->orderBy('updated_at', 'DESC')->paginate(50);
        }



        foreach ($statuts as $statut){
            $statutStat[$statut->statut] = $statut->total;
        }


        foreach ($commandes as $commande) {
            if (!empty(User::withTrashed()->find($commande->user_id)))
                $users[] =  User::withTrashed()->find($commande->user_id);
        }
        //$commandes = Commande::all()->paginate(3) ;
        $checkBox = 0;
        return view('commande.colis', [
            'nouveau' => $nouveau, 'commandes' => $commandes,
            'total' => $total,
            'users' => $users,
            'clients' => $clients,
            'livreurs' => $livreurs,
            'produits' => $produits,
            'villes' => $villes,
            'data' => $data, 'checkBox' => $checkBox,
            'statutStat' => $statutStat
        ]);
    }


    public function outRange(Commande $commande, Request $request){
        $commande->ville .= ' (Hors Zone)';
        $commande->prix = $request->horsZone;
        $commande->livreurPart = $request->horsZoneLivreurPart;
        $commande->save();
        $request->session()->flash('statut', 'modifié');
        return back();
    }

    public function change(Commande $commande, Request $request){
        $commande->isChanged = 1;
        $commande->save();
        $request->session()->flash('statut', 'modifié');
        return back();
    }


    public function filter(Request $request)
    {
        $statuts = [];
        $statutStat = [];


        $commandes = Commande::where('commandes.deleted_at', NULL);
        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client', 'ecom']);
        })->orderBy('name')->get();
        $livreurs = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['livreur']);
        })->orderBy('name')->get();
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();
        $villes = DB::table('villes')->orderBy('name')->get();
        $checkBox = 1;
        $page = 50;

        $users = [];
        $produits = [];
        $data = $request->all();
        if (!Gate::denies('ecom')) {
            $produits = Produit::where('user_id', Auth::user()->id)->get();
        }

        if (!Gate::denies('manage-users')) {
            $produits_total = Produit::get();
            foreach ($produits_total as $produit) {
                $stock = DB::table('stocks')->where('produit_id', $produit->id)->get();
                if ($stock[0]->qte > 0) {
                    $produits[] = $produit;
                }
            }
        }


        if (Gate::denies('ramassage-commande')) { //session client donc on cherche seulement dans ses propres commandes
            $commandes->where('user_id', Auth::user()->id);
            $clients = null;
            $livreurs = null;
        }

        if (!Gate::denies('livreur')) {
            $commandes->where('livreur', Auth::user()->id);
        }

        if ($request->filled('produit')) {
            $productId = $request->produit;
            $cmdIds =[];
            $productFiltred = Produit::find($productId);

            foreach ($productFiltred->commandes()->get() as $cmd) {
                $cmdIds[] = $cmd->id;
            }
            $commandes->whereIn('id',$cmdIds);
        }
        if ($request->filled('statut')) {
            //dd("salut");
            if (!Gate::denies('livreur')) {
                $Ramassage = array("envoyée", "Ramassée", "Reçue",'En attente de ramassage');
                if (in_array($request->statut, $Ramassage)) {
                return back();
                } else {
                    $commandes->where('livreur', Auth::user()->id);

                    $commandes->where('statut', 'like', '%' . $request->statut . '%');
                }
            } else {

                $commandes->where('statut', 'like', '%' . $request->statut . '%');
            }

        }

        if (Gate::denies('livreur')) {
            if (!Gate::denies('manage-users')) {

                if ($request->filled('client')) {
                    $commandes->where('user_id', $request->client);
                }
                if ($request->filled('livreur')) {
                    $page = 50;
                    $commandes->where('commandes.livreur', $request->livreur)
                        ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue','En attente de ramassage']);
                }
            }

            if ($request->filled('ville')) {
                $commandes->where('ville', $request->ville);
            }
        }


        if ($request->filled('nom')) {
            $commandes->where('nom', 'like', '%' . $request->nom . '%');
        }
        if ($request->filled('numero')) {
            $commandes->where('numero', 'like', '%' . $request->numero . '%');
        }
        if ($request->filled('telephone')) {
            $commandes->where('telephone', 'like', '%' . $request->telephone . '%');
        }

        if ($request->filled('dateMin')) {
            $commandes->whereDate('updated_at', '>=', $request->dateMin);
        }
        if ($request->filled('dateMax')) {
            $commandes->whereDate('updated_at', '<=', $request->dateMax);
        }
        if ($request->filled('prixMin') && $request->prixMin > 0) {
            $commandes->where('montant', '>=', $request->prixMin);
        }
        if ($request->filled('prixMax') && $request->prixMax > 0) {
            $commandes->where('montant', '<=', $request->prixMax);
        }
        if ($request->filled('facturer')) {
            if($request->facturer == "no") $commandes->where('facturer', 0);
            else if($request->facturer == "yes") $commandes->where('facturer', '<>', 0);
        }
        if (Gate::denies('livreur')) {
            $commandes = $commandes->where(function ($q) {
                $q->whereDate('updated_at', '>=', now()->subMonth())
                    ->orWhereNotIn('commandes.statut', ['livré', 'Retour en stock']);
            });
        }
        $total = $commandes->count();
        $commandes = $commandes->orderBy('updated_at', 'DESC')->paginate($page);
        foreach ($commandes as $commande) {
            if (!empty(User::withTrashed()->find($commande->user_id)))
                $users[] =  User::withTrashed()->find($commande->user_id);
        }


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
                ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue','En attente de ramassage'])
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
            'data' => $data, 'checkBox' => $checkBox,
            'statutStat' => $statutStat

        ]);
    }



    public function search(Request $request)
    {
        $data = $request->all();
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();
        $villes = DB::table('villes')->orderBy('name')->get();

        if (Gate::denies('livreur')) {
            if (strcmp(substr($request->search, -strlen($request->search), 4), "FAC_") == 0) {
                $clients = [];
                $users = [];
                if (!Gate::denies('manage-users')) {
                    $factures = DB::table('factures')->where('numero', 'like', '%' . $request->search . '%')->paginate(10);
                    $clients = User::whereHas('roles', function ($q) {
                        $q->whereIn('name', ['client', 'ecom']);
                    })->get();
                } else {
                    $factures = DB::table('factures')->where('user_id', Auth::user()->id)->where('numero', 'like', '%' . $request->search . '%')->paginate(10);
                }
                $total = $factures->count();

                foreach ($factures as $facture) {
                    if (!empty(User::withTrashed()->find($facture->user_id)))
                        $users[] =  User::withTrashed()->find($facture->user_id);
                }
                if ($total > 0) {
                    //dd($factures);
                    return view('facture', [
                        'factures' => $factures, 'nouveau' => $nouveau,
                        'total' => $total,
                        'users' => $users,
                        'clients' => $clients,
                        'villes' => $villes, 'data' => $data

                    ]);
                } else {
                    $request->session()->flash('search', $request->search);
                    return redirect()->route('facture.index');
                }
            }


            if (strcmp(substr($request->search, -strlen($request->search), 3), "BL_") == 0) {
                $clients = [];
                $id_bon = (int)substr($request->search, 9);
                $data = $request->all();

                if (!Gate::denies('manage-users')) {
                    $bonLivraisons = DB::table('bon_livraisons')->where('id', $id_bon);
                    //dd($bonLivraisons->count());
                    $clients = User::whereHas('roles', function ($q) {
                        $q->whereIn('name', ['client', 'ecom']);
                    })->get();
                } else {
                    $bonLivraisons = DB::table('bon_livraisons')->where('user_id', Auth::user()->id)->where('id', $id_bon);
                }
                $total = $bonLivraisons->get()->count();
                $bonLivraisons = $bonLivraisons->paginate(10);


                foreach ($bonLivraisons as $bonLivraison) {
                    if (!empty(User::withTrashed()->find($bonLivraison->user_id)))
                        $users[] =  User::withTrashed()->find($bonLivraison->user_id);
                }
                if ($total > 0) {
                    $ramasse = Commande::where('user_id', Auth::user()->id)->where('statut', 'Rammasée')->where('traiter', '0')->count();
                    $nonRammase = Commande::where('user_id', Auth::user()->id)->where('statut', 'envoyée')->where('traiter', '0')->count();

                    //dd($bonLivraisons);
                    return view('bonLivraison', [
                        'bonLivraisons' => $bonLivraisons, 'nouveau' => $nouveau,
                        'total' => $total,
                        'users' => $users,
                        'clients' => $clients,
                        'ramasse' => $ramasse,
                        'nonRamasse' => $nonRammase,
                        'villes' => $villes, 'data' => $data
                    ]);
                } else {
                    $request->session()->flash('search', $request->search);
                    return redirect()->route('bonlivraison.index');
                }
            }
        }


        $users = [];
        $produits = [];
        if (!Gate::denies('manage-users')) {
            //session administrateur donc on affiche tous les commandes
            $total = Commande::where('numero', 'like', '%' . $request->search . '%')->where('deleted_at', NULL)->count();
            $commandes = Commande::where('numero', 'like', '%' . $request->search . '%')->where('deleted_at', NULL)->orderBy('created_at', 'DESC')->paginate(50);
        } elseif (!Gate::denies('livreur')) {
            $request->session()->flash('search', $request->search);
            return redirect()->route('commandes.index');
        } else {
            $commandes = Commande::where('numero', 'like', '%' . $request->search . '%')->where('deleted_at', NULL)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(50);
            $total = Commande::where('numero', 'like', '%' . $request->search . '%')->where('deleted_at', NULL)->where('user_id', Auth::user()->id)->count();
        }

        if ($total == 0) { //recherche par statut
            if (!Gate::denies('ramassage-commande')) {
                //session administrateur donc on affiche tous les commandes
                $total = Commande::where('statut', 'like', '%' . $request->search . '%')->where('deleted_at', NULL)->count();
                $commandes = Commande::where('statut', 'like', '%' . $request->search . '%')->where('deleted_at', NULL)->orderBy('created_at', 'DESC')->paginate(50);
            } else {
                $commandes = Commande::where('statut', 'like', '%' . $request->search . '%')->where('deleted_at', NULL)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(50);
                $total = Commande::where('statut', 'like', '%' . $request->search . '%')->where('deleted_at', NULL)->where('user_id', Auth::user()->id)->count();
            }
        }
        //dd($commandes);
        if ($total > 0) {
            $data = $request->all();

            if (!Gate::denies('ecom')) {
                $produits = Produit::where('user_id', Auth::user()->id)->get();
                //dd($produits);
            }
            if (!Gate::denies('manage-users')) {
            $produits_total = Produit::get();
            foreach ($produits_total as $produit) {
                $stock = DB::table('stocks')->where('produit_id', $produit->id)->get();
                if ($stock[0]->qte > 0) {
                    $produits[] = $produit;
                }
            }
        }
            foreach ($commandes as $commande) {
                if (!empty(User::withTrashed()->find($commande->user_id)))
                    $users[] =  User::withTrashed()->find($commande->user_id);
            }
            $livreurs = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['livreur']);
            })->get();

            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->get();
            $checkBox = 0;
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
                ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue','En attente de ramassage'])
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
            'data' => $data, 'checkBox' => $checkBox,
            'statutStat' => $statutStat

        ]);
        } else {
            $request->session()->flash('search', $request->search);
            return redirect()->route('commandes.index');
        }
    }


    private function mapQuantity($quantity)
    {
        return collect($quantity)->map(function ($i) {
            return ['qte' => $i->qte , 'produit_id'=> $i->produit_id];
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::denies('manage-users')) {
            if (isset($request->client)) {
                $fournisseur = User::find($request->client);
            } else {
                return redirect('/commandes');
            }
        } else {
            $fournisseur = Auth::user();
        }

        $commande = new Commande();
        $statut = new Statut();

        //prix de livraison de la commande
        if ($fournisseur->prix === 0) {
            $commande->prix = DB::table('villes')
                ->select('prix')
                ->where('name', $request->ville)
                ->get()->first()->prix;
        } else {
            $commande->prix = $fournisseur->prix;
        }

        // //la part du livreur
        // $commande->livreur = DB::table('villes')
        //     ->select('livreur')
        //     ->where('name', $request->ville)
        //     ->get()->first()->livreur;

        if ($request->mode == "cd" && Gate::denies('ecom')) {
            $commande->montant = $request->montant;
        } else {
            $commande->montant = 0;
        }
        $commande->telephone = $request->telephone;
        $commande->ville = $request->ville;
        $commande->secteur = ($request->secteur) ? $request->secteur : $request->ville;
        $commande->adresse = $request->adresse;
        $commande->statut = "envoyée";
        $commande->colis = 1;
        $commande->poids = '';
        $commande->nom = $request->nom;
        $commande->traiter = 0;
        $commande->facturer = 0;
        $commande->numero = substr($fournisseur->name, -strlen($fournisseur->name), 2) . "-" . date("md-is") . "-" . $this->unique_code(4);
        $commande->isOpen = $request->isOpen;
        $commande->is_fragile = ($request->isFragile) ? 1 : 0;
        $commande->isChanged = $request->isChanged;
        $livreurForCmd = User::where('ville', 'like',  '%' . $request->ville . ',%')->whereHas('roles', function ($q) {
            $q->whereIn('name', ['livreur']);
        })->first();

        $commande->livreur = $livreurForCmd == null ? 1 : $livreurForCmd->id;

        $ville = DB::table('villes')->where('name', $commande->ville)->first();
        $commande->livreurPart = $ville == null ? 15 : $ville->livreur;
        $commande->refusePart = $ville == null ? 10 : $ville->refuse;

        if (!Gate::denies('ecom')) {
            if (isset($request->produit)) {
                foreach ($request->produit as $index => $IdProduit) {
                    $produit = Produit::find($IdProduit);
                    $prixProduit = $produit->prix * $request->qte[$index];

                    $commande->montant += $prixProduit;
                    if($produit->is_fragile){
                        $commande->is_fragile = $produit->is_fragile;
                    }

                    $stock = Stock::where('produit_id', $IdProduit)->first();
                    //verification du stock
                    if ($stock->qte >= $request->qte[$index]) {
                        $stock->qte -= $request->qte[$index];
                        $stock->save();
                    } else {
                        $request->session()->flash('stock_insuf', $produit->libelle);
                        return redirect('/commandes');
                    }
                }
            }
            else{
                $request->session()->flash('produit_required');
                return redirect('/commandes');
            }

            if ($request->mode == "cp") {
                $commande->montant = 0;
            }
            else if ($request->mode != "cp" && $request->montant !== null) {
                $commande->montant = $request->montant;
            }
        }

        if($request->mode != "cp" && $request->montant == null && Gate::denies('ecom')){
            $request->session()->flash('montant_required');
            return back();
        }

        $commande->user()->associate($fournisseur)->save();
        $this->getQrCode($commande);

        if (!Gate::denies('ecom') && isset($request->produit)) {
            $produit_commandes = [];
            foreach ($request->produit as $index => $produit) {
                $produit_commande = new CommandeProduit();
                $produit_commande->qte =  $request->qte[$index];
                $produit_commande->produit_id =  $request->produit[$index];
                $produit_commandes[] = $produit_commande;
            }
            $commande->produits()->sync($this->mapQuantity($produit_commandes));

        }

        //$commande->save();
        $statut->commande_id = $commande->id;
        $statut->name = $commande->statut;
        $statut->user()->associate(Auth::user())->save();
        $request->session()->flash('statut', $commande->id);


        //notification
        // $user_notify = \App\User::find(1);
        // $user_notify->notify(new newCommande($fournisseur, $commande));


        return redirect('/commandes');
    }


    public function showFromNotify(Commande $commande, DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return redirect()->route('commandes.show', $commande->id);
    }


    public function affecterLivreur(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        $commande->livreur = $request->livreur;
        $commande->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Commande  $commande
     * @return \Illuminate\Http\Response
     */
    public function show(Commande $commande)
    {
       //dd($commande->produits()->first());
        // dd(DB::getQueryLog());

        $users[] = "";
        $livreurs = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['livreur']);
        })->get();

        $livreurAffected =  User::find($commande->livreur);
        if ($livreurAffected == null) $livreurAffected =  User::find(Auth::user()->id);

        $villes = DB::table('villes')->orderBy('name')->get();
        $fournisseur = User::find($commande->user_id);
        $client = false;
        if ($fournisseur != null && $fournisseur->hasRole('ecom')) $client = true;

        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();
        $etat = array("Injoignable", "Annulée", "Retour", "Pas de Réponse", "envoyée", "Refusée",'En attente de ramassage');
        if ((Gate::denies('client-admin') || $commande->statut !== "envoyée" || $commande->statut !== 'En attente de ramassage') && (Gate::denies('manage-users') || !in_array($commande->statut, $etat))) {

            $modify = 0;
        } else $modify = 1;
        if ($commande->facturer > 0) $modify = 0;

        if (!Gate::denies('livreur')) {
            $Ramassage = array("envoyée", "Ramassée", "Reçue",'En attente de ramassage');

            if ($commande->livreur !== Auth::user()->id || in_array($commande->statut, $Ramassage))
                return redirect()->route('commandes.index');
        }

        if (Gate::denies('ramassage-commande')) {
            if ($commande->user_id !== Auth::user()->id)
                return redirect()->route('commandes.index');
        }

        $etat = array("Injoignable", "Refusée", "Retour");


        //return $commande;
        //dd($produits);
        $statuts = DB::table('statuts')->where('commande_id', $commande->id)->get();
        foreach ($statuts as $statut) {
            $users[] =  User::withTrashed()->find($statut->user_id);
        }
        $total = DB::table('relances')->where('commande_id', $commande->id)->count();
        $relances = DB::table('relances')->where('commande_id', $commande->id)->get();
        $Rpar = null;
        foreach ($relances as $relance) {
            $Rpar[] =  User::find($relance->user_id); //relancée par
        }

        if (!Gate::denies('gestion-stock')) {
            $produits = [];
            $liaisons = DB::table('commande_produit')->where('commande_id', $commande->id)->get();
            foreach ($liaisons as $produit) {
                $produits[] = Produit::find($produit->produit_id);
            }
            return view('commande.show', [
                'commande' => $commande, 'statuts' => $statuts, 'nouveau' => $nouveau,
                'par' => $users,
                'produits' => $produits,
                'liaisons' => $liaisons,
                'relances' => $relances,
                'Rpar' => $Rpar,
                'Rtotal' => $total,
                'modify' => $modify,
                'villes' => $villes,
                'client' => $client, 'livreurs' => $livreurs,
                'livreur' => $livreurAffected
            ]);
        }
        //dd($users);
        return view('commande.show', [
            'commande' => $commande, 'statuts' => $statuts, 'nouveau' => $nouveau,
            'par' => $users,
            'relances' => $relances,
            'Rpar' => $Rpar,
            'Rtotal' => $total,
            'modify' => $modify,
            'villes' => $villes,
            'client' => $client, 'livreurs' => $livreurs,
            'livreur' => $livreurAffected

        ]);
    }


    public function getQrCode($commande){
        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.url('/').'/commandes/'.$commande->id);
        // Get the content of the response
        $imageContent = $response->body();
        // Generate a unique filename for the decoded image
        $fileName = 'qr_code_' . $commande->numero . '.png';
        // Save the decoded image to the public directory
        file_put_contents(public_path('uploads/commandesQRCODE/' . $fileName), $imageContent);
    }


    public function getFileNameForQrCode($commandes){
        $filesName = [];
        foreach ($commandes as $key => $commande) {
            $fileName = 'qr_code_' . $commande->numero . '.png';
            $filesName[] = $fileName;
        }
        return $filesName;
    }


    public function ticketsBuilderHelper($commandes, $filesName, $format)
    {
        $pdf = app('dompdf.wrapper')->loadView('pdf.ticket', ['commandes' => $commandes,'filesName' => $filesName, 'size' => $format])->setPaper($format);
        return $pdf->stream('ticket-colis.pdf');
    }


    public function ticketsBuilder(Request $request)
    {
        $commandes = Commande::where('deleted_at', NULL)->whereIn('id', $request->item)->get();
        return $this->ticketsBuilderHelper($commandes,$this->getFileNameForQrCode($commandes), 'A6');
    }

    public function gen($id)
    {
        $commandes = Commande::where('deleted_at', NULL)->where('id', $id)->get();
        return $this->ticketsBuilderHelper($commandes, $this->getFileNameForQrCode($commandes), 'A6');
    }

    public function genA8($id)
    {
        $commandes = Commande::where('deleted_at', NULL)->where('id', $id)->get();
        return $this->ticketsBuilderHelper($commandes, $this->getFileNameForQrCode($commandes), 'A8');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Commande  $commande
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCommande $request, Commande $commande)
    {
        if (Gate::denies('ramassage-commande')) {
            if ($commande->user_id !== Auth::user()->id)
                return redirect()->route('commandes.index');
        }
        $etat = array("envoyée","Injoignable", "Refusée", "Annulée", "Retour", "Pas de Réponse");
        $ancienne = $commande->statut;
        if ( Gate::denies('delete-commande') || !in_array($commande->statut, $etat)) {
            //dd( $commande->staut );
            $request->session()->flash('noupdate', $commande->numero);
            return back();
        } else {
            $newCommande = new Commande();

            $newCommande->numero = $commande->numero . "-M";
            $newCommande->telephone = $commande->telephone;
            $newCommande->ville = $commande->ville;
            $newCommande->secteur = $commande->secteur;
            $newCommande->adresse = $commande->adresse;
            $newCommande->prix = $commande->prix;
            $newCommande->statut = $commande->statut;
            $newCommande->colis = $commande->colis;
            $newCommande->poids = $commande->poids;
            $newCommande->montant = $commande->montant;
            $newCommande->nom = $commande->nom;
            $newCommande->traiter = $commande->traiter;
            $newCommande->facturer = $commande->facturer;
            $newCommande->livreur = $commande->livreur;
            $newCommande->user_id = $commande->user_id;
            $newCommande->livreurPart = $commande->livreurPart;
            $newCommande->refusePart = $commande->refusePart;
            $newCommande->isOpen = $commande->isOpen;
            $newCommande->is_fragile = $commande->is_fragile;
            $newCommande->isChanged = $commande->isChanged;



            if ($request->mode == "cd") {
                $commande->montant = $request->montant;
            } else {
                $commande->montant = 0;
            }

            $commande->livreur = DB::table('villes')
                ->select('livreur')
                ->where('name', $request->ville)
                ->get()->first()->livreur;

            if ($commande->user()->first()->prix === 0) {
                $commande->prix = DB::table('villes')
                    ->select('prix')
                    ->where('name', $request->ville)
                    ->get()->first()->prix;
            }

            $commande->facturer = 0;
            $commande->telephone = $request->telephone;
            $commande->ville = $request->ville;
            $commande->adresse = $request->adresse;
            $commande->secteur = $request->ville;


            $livreurForCmd = User::where('ville', 'like', '%' . $request->ville . ',%')->whereHas('roles', function ($q) {
                $q->whereIn('name', ['livreur']);
            })->first();
            $commande->livreur = $livreurForCmd == null ? 1 : $livreurForCmd->id;

            $ville = DB::table('villes')->where('name', $request->ville)->first();
            $commande->livreurPart = $ville == null ? 15 : $ville->livreur;
            $commande->refusePart = $ville == null ? 10 : $ville->refuse;

            //dd($request->secteur);


            $commande->colis = $request->colis;
            $commande->nom = $request->nom;

            if (!Gate::denies('delete-commande') && in_array($ancienne, $etat)) {

                if ($ancienne === "Refusée") {
                    $statut = new Statut();
                    $newCommande->save();
                    $statut->commande_id = $newCommande->id;
                    $statut->name = $newCommande->statut;
                    $statut->user()->associate(Auth::user())->save();
                    // if (!Gate::denies('ecom')) {
                    //     foreach ($request->produit as $index => $produit) {

                    //         $produit_commande = new CommandeProduit();
                    //         $produit_commande->commande_id = $newCommande->id;
                    //         $produit_commande->produit_id = $produit;
                    //         $produit_commande->qte =  $request->qte[$index];
                    //         $produit_commande->save();
                    //     }
                    // }
                }
                $commande->statut = ($ancienne === "envoyée") ? "envoyée" :  "Modifiée";
                $commande->relance = null;
                $commande->save();
                $statut = new Statut();
                $statut->commande_id = $commande->id;
                $statut->name = $commande->statut;
                $statut->user()->associate(Auth::user())->save();
            } else $commande->save();

            $request->session()->flash('statut', 'modifié');
        }
        return redirect()->route('commandes.show', ['commande' => $commande->id]);
    }


    public function changeStatut(Request $request, $id)
    {
        //changement de statut du expidé à en cours
        //dd(!Gate::denies('ramassage-commande'));
        $commande = Commande::findOrFail($id);
        //$factureExist = DB::table('factures')->where('user_id',$commande->user_id )->whereDate('created_at',$commande->created_at)->count();

        if (Gate::denies('ramassage-commande')) {
            $request->session()->flash('noedit', $commande->numero);
            return redirect(route('commandes.index'));
        }
        //pour traiter la commande à ramassée , faut verifier deux conditons:
        // commande est envoyée + traiter = 0

        if (($commande->statut === "envoyée" || $commande->statut === "Reçue" || $commande->statut === "Ramassée" || $commande->statut === "Expédiée")) {
            $user_ville = User::findOrFail($commande->user_id);
            if ($commande->statut === "envoyée")
                $commande->statut = "Reçue";

            elseif ($commande->statut === "Ramassée") {
                if (!Gate::denies('livreur')) return back();

                if ($user_ville->ville == $commande->ville || $commande->ville == "Rabat") {
                    $commande->statut = "En cours";
                } else $commande->statut = "Reçue";
            } elseif ($commande->statut === "Reçue") {

                if (!Gate::denies('livreur')) return back();
                $commande->statut = "Expédiée";
            } elseif ($commande->statut === "Expédiée") {
                $commande->statut = "En cours";
            } else {
                if ($user_ville->ville == $commande->ville || $commande->ville == "Rabat") {
                    $commande->statut = "En cours";
                } else {
                    $commande->statut = "Reçue";
                }
            }


            $commande->save();
            $statut = new Statut();
            $statut->commande_id = $commande->id;
            $statut->name = $commande->statut;
            $statut->user()->associate(Auth::user())->save();

            //notification
            // $user_notify = \App\User::find($commande->user_id);
            // $user_notify->notify(new statutChange($commande));
            //dd($test);
            $request->session()->flash('edit', $commande->numero);
        } else {
            if ($commande->statut != "envoyée") {
                $request->session()->flash('nonExpidie', $commande->numero);
            } else {
                $request->session()->flash('blgenere', $commande->numero);
            }
        }

        return back();
    }


    public function relanceCommandeByClient(Request $request, $commandeId){

        $commande = Commande::findOrFail($commandeId);
        $fournisseur = Auth::user();
        if (!Gate::denies('fournisseur') && ($commande->statut === "Pas de Réponse" || $commande->statut === "Annulée" ||  $commande->statut === "Injoignable")) {
            $commande->statut = "Relancée";
            $commande->save();

           $request->session()->flash('edit', $commande->numero);

        }
         return back();
    }


    public function retourStock(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

        $fournisseur = User::find($commande->user_id);
        if ($fournisseur->hasRole('ecom') && ($commande->statut === "Retour" || $commande->statut === "Pas de Réponse" || $commande->statut === "Annulée" || $commande->statut === "Refusée" || $commande->statut === "Injoignable")) {

            if ($commande->statut === "Refusée" && $commande->facturer == 0) {
                $request->session()->flash('cmdRefuser', $commande->numero);
            } else {
                $commande->statut = "Retour en stock";
                $statut = new Statut();
                $statut->commande_id = $commande->id;
                $statut->name = $commande->statut;
                $commande_produits = DB::table('commande_produit')->where('commande_id', $commande->id)->get();
                $statut->user()->associate(Auth::user())->save();
                $commande->save();
                foreach ($commande_produits as $commande_produit) {
                    $stock = Stock::where('produit_id', $commande_produit->produit_id)->first();
                    $stock->qte += $commande_produit->qte;
                    $stock->save();
                }
            }
        }

        return back();
    }


    public function updateSatuts(Request $request){

        if ($request->item == null) return back();

        $commandes = Commande::whereIn('id',$request->item)->get();

        if (!Gate::denies('manage-users')) {
            foreach ($commandes as $commande) {
                $commande->statut = $request->newStatut;
                $commande->commentaire = $request->commentaire;
                $commande->postponed_at = $request->prevu_at;

                $statut = new Statut();
                $statut->commande_id = $commande->id;
                $statut->postponed_at = $commande->postponed_at;

                $statut->name = $commande->statut;
                $statut->user()->associate(Auth::user())->save();
                $commande->save();
            }
            $request->session()->flash('editBatch', count($request->item));
        }
        return redirect()->route('commandes.index');
    }


    public function expedier(Request $request){

        if ($request->item == null) return back();

        $commandes = Commande::whereIn('id',$request->item)->get();

        if (!Gate::denies('manage-users')) {
            foreach ($commandes as $commande) {
                $commande->statut = 'Expédiée';
                $commande->commentaire = $request->commentaire;
                $commande->postponed_at = $request->prevu_at;

                $statut = new Statut();
                $statut->commande_id = $commande->id;
                $statut->postponed_at = $commande->postponed_at;
                $statut->name = $commande->statut;
                $statut->user()->associate(Auth::user())->save();
                $commande->save();
            }
            $request->session()->flash('editBatch', count($request->item));
        }
        return redirect()->route('commandes.index');
    }


    public function recevoir(Request $request){

        if ($request->item == null) return back();

        $commandes = Commande::whereIn('id',$request->item)->get();

        if (!Gate::denies('manage-users')) {
            foreach ($commandes as $commande) {
                $commande->statut = 'Reçue';
                $commande->commentaire = $request->commentaire;
                $commande->postponed_at = $request->prevu_at;

                $statut = new Statut();
                $statut->commande_id = $commande->id;
                $statut->postponed_at = $commande->postponed_at;
                $statut->name = $commande->statut;
                $statut->user()->associate(Auth::user())->save();
                $commande->save();
            }
            $request->session()->flash('editBatch', count($request->item));
        }
        return redirect()->route('commandes.index');
    }

    public function statutAdmin(Request $request, $id)
    {
        $finalLivreurState = array("En cours","Livré", "Injoignable", "Pas de Réponse", "Refusée", "Annulée", "Reporté", "Retour"); // les états finaux
        $commande = Commande::findOrFail($id);
        $user = User::find($commande->user_id);

        if($request->statut === $commande->statut){
            return back();
        }

        if($commande->facturer > 0 && $commande->statut === "Livré"){
            $request->session()->flash('no-edit-invoiced', $commande->numero);
            return back();
        }
        else if($commande->facturer > 0 && $request->statut === "Retour"){
            $commande->statut = $request->statut ;
            $statut = new Statut();
            $statut->commande_id = $commande->id;
            $statut->name = $commande->statut;
            $commande->save();
            $statut->user()->associate(Auth::user())->save();
            return  back();
        }

        else if($commande->facturer > 0 && $commande->statut === "Refusée" ){

            $newCommande = new Commande();

            $newCommande->numero = $commande->numero . "-D";
            $newCommande->telephone = $commande->telephone;
            $newCommande->ville = $commande->ville;
            $newCommande->secteur = $commande->secteur;
            $newCommande->adresse = $commande->adresse;
            $newCommande->prix = $commande->prix;
            $newCommande->colis = $commande->colis;
            $newCommande->poids = $commande->poids;
            $newCommande->montant = $commande->montant;
            $newCommande->nom = $commande->nom;
            $newCommande->traiter = $commande->traiter;
            $newCommande->facturer = 0;
            $newCommande->livreur = $commande->livreur;
            $newCommande->user_id = $commande->user_id;
            $newCommande->livreurPart = $commande->livreurPart;
            $newCommande->refusePart = $commande->refusePart;
            $newCommande->isOpen = $commande->isOpen;
            $newCommande->is_fragile = $commande->is_fragile;
            $newCommande->isChanged = $commande->isChanged;

            $newCommande->statut = $request->statut;

            $newCommande->save();
            $statut = new Statut();
            $statut->commande_id = $newCommande->id;
            $statut->name = $newCommande->statut;
            $statut->user()->associate(Auth::user())->save();

            $commande->statut = "Retour en stock";
            $commande->save();
            $statut = new Statut();
            $statut->commande_id = $commande->id;
            $statut->name = $commande->statut;
            $statut->user()->associate(Auth::user())->save();


            return back();
        }

            //Session Administrateur
        if (!Gate::denies('manage-users')) {
            $commande->statut = $request->statut;
            $commande->commentaire = $request->commentaire;
            $commande->postponed_at = $request->prevu_at;

            if ($commande->statut !== 'Livré' && $user->statut === 1) {
                $commande->relance = 0;
            }
            $statut = new Statut();
            $statut->commande_id = $commande->id;
            $statut->postponed_at = $commande->postponed_at;
            $statut->name = $commande->statut;
            $statut->user()->associate(Auth::user())->save();
            $commande->save();

            // Nexmo::message()->send([
            //     'to'   => '212'.substr($commande->telephone,1),
            //     'from' => 'Rafex Delivery',
            //     'text' => 'Bonjour '.$commande->nom.' Votre Commande  '.$commande->numero.' de la part du '. $user->name .' a été bien livrée.'
            // ]);

            $request->session()->flash('edit', $commande->numero);
            return back();
        }

            //Session Livreur
        if (!Gate::denies('livreur') && in_array($commande->statut, $finalLivreurState) && in_array($request->statut, $finalLivreurState) && $commande->facturer == 0) {
            $commande->statut = $request->statut;
            $statut = new Statut();
            $statut->commande_id = $commande->id;
            $statut->name = $commande->statut;
            $commande->commentaire = $request->commentaire;
            $commande->postponed_at = $request->prevu_at;
            $statut->postponed_at = $commande->postponed_at;


            if ($commande->statut !== 'Livré' && $user->statut === 1) {
                $commande->relance = 0;
            }
            $statut->user()->associate(Auth::user())->save();
            $commande->save();

            $request->session()->flash('edit', $commande->numero);
            return back();
        }

        if (Gate::denies('ramassage-commande') || $commande->statut === 'envoyée') {
            $request->session()->flash('noedit', $commande->numero);
        } else {
            $Ramassage = array("Livré", "Injoignable", "Pas de Réponse", "Refusée", "Annulée", "Reporté", "Retour"); // les états finaux
            if (in_array($request->statut, $Ramassage)) { //verification du nouveau statut
                //verification de l'ancien statut
                if ($commande->statut === 'Annulée' || $commande->statut === 'Retour' || $commande->statut === 'Injoignable' || $commande->statut === 'Pas de Réponse' || $commande->statut === 'En cours' || $commande->statut === 'Modifiée' || $commande->statut === 'Relancée' || $commande->statut === 'Reporté') { //bach traiter commande khass tkoun en cours w bl dyalha kyn
                    $commande->statut = $request->statut;
                    $commande->commentaire = $request->commentaire;

                    $commande->postponed_at = $request->prevu_at;

                    if ($commande->statut !== 'Livré' && $user->statut === 1) {
                        $commande->relance = 0;
                    }
                    $statut = new Statut();
                $statut->postponed_at = $commande->postponed_at;
                    $statut->commande_id = $commande->id;
                    $statut->name = $commande->statut;
                    $statut->user()->associate(Auth::user())->save();
                    $request->session()->flash('edit', $commande->numero);


                    $commande->save();
                } else {

                    $request->session()->flash('noedit', $commande->numero);
                }
            } else {
                $request->session()->flash('noedit', $commande->numero);
            }
        }


        return back();
    }


    public function relancer(Request $request, $id)
    {
        if (!Gate::denies('ramassage-commande')) {
            $commande = Commande::findOrFail($id);
            $total = DB::table('relances')->where('commande_id', $commande->id)->count();
            if ($total < 3) {
                $relance = new Relance();
                $relance->commande_id = $commande->id;
                $relance->comment = $request->comment;
                $relance->user()->associate(Auth::user())->save();
                $request->session()->flash('relance', $commande->numero);
            }
        }

        return back();
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Commande  $commande
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Commande $commande)
    {

            if (!Gate::denies('edit-users')) {
            $statut = DB::table('statuts')->where('commande_id', $commande->id)->get()->first();
            \App\Statut::destroy($statut->id);
            \App\Commande::destroy($commande->id);

            $request->session()->flash('delete', $commande->numero);
            return redirect('/commandes');
        }

        if (Gate::denies('delete-commande')) {
            //dd('salut');
            $request->session()->flash('nodelete', $commande->numero);
            return redirect()->route('commandes.show', ['commande' => $commande->id]);
        }



        $etat = array("Injoignable", "Refusée", "Retour");


        if ($commande->statut === "envoyée" || (!Gate::denies('manage-users') && in_array($commande->statut, $etat))) {

            $numero = $commande->numero;
            $statut = DB::table('statuts')->where('commande_id', $commande->id)->get()->first();
            //dd($statut->id);



            $commande_produits = DB::table('commande_produit')->where('commande_id', $commande->id)->get();
            if ($commande_produits->count() > 0) {
                foreach ($commande_produits as $commande_produit) {
                    $stock = Stock::where('produit_id', $commande_produit->produit_id)->first();
                    $stock->qte += $commande_produit->qte;
                    $stock->save();
                }
            }


            \App\Statut::destroy($statut->id);
            \App\Commande::destroy($commande->id);

            $request->session()->flash('delete', $numero);
            return redirect('/commandes');
        } else {
            //dd($commande->statut);
            $request->session()->flash('nodelete', $commande->numero);
            return redirect()->route('commandes.show', ['commande' => $commande->id]);
        }
    }

    function unique_code($limit)
    {
        return substr(uniqid(mt_rand(), 16), 0, $limit);
    }
}
