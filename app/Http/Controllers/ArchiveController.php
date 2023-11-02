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

class ArchiveController extends Controller
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
        //dd(auth()->user()->unreadNotifications );
        //dd(Auth::user()->id );
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
            //session administrateur donc on affiche tous les commandes
            $total = DB::table('commandes')->where('deleted_at', NULL)->whereDate('updated_at', '<', now()->subMonth())->whereIn('commandes.statut', ['livré', 'Retour en stock', 'Retour', 'Retour'])->count();
            $commandes = DB::table('commandes')->where('deleted_at', NULL)->whereDate('updated_at', '<', now()->subMonth())->whereIn('commandes.statut', ['livré', 'Retour en stock', 'Retour', 'Retour'])->orderBy('updated_at', 'DESC')->paginate(10);

            //dd($clients[0]->id);
        } elseif (!Gate::denies('livreur')) {
            //session livreur
            //dd("test");

            $total = DB::table('commandes')
                ->join('users', 'users.id', '=', 'commandes.user_id')
                ->select('commandes.*', 'users.image')
                ->where('commandes.deleted_at', NULL)->whereDate('updated_at', '<', now()->subMonth())
                ->where(function ($query) {
                    $userVilles = array_filter(explode(",", Auth::user()->ville));

                    $query->whereIn('commandes.ville', $userVilles)
                        ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue']);
                })->count();

            $commandes = DB::table('commandes')
                ->join('users', 'users.id', '=', 'commandes.user_id')
                ->select('commandes.*', 'users.image')
                ->where('commandes.deleted_at', NULL)->whereDate('updated_at', '<', now()->subMonth())
                ->where(function ($query) {
                    $userVilles = array_filter(explode(",", Auth::user()->ville));

                    $query->whereIn('commandes.ville', $userVilles)
                        ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue']);
                })->orderBy('commandes.updated_at', 'DESC')->paginate(10);

            //dd($clients[0]->id);
        } else {
            $commandes = DB::table('commandes')->where('deleted_at', NULL)->where('user_id', Auth::user()->id)->whereDate('updated_at', '<', now()->subMonth())->whereIn('commandes.statut', ['livré', 'Retour en stock', 'Retour'])->orderBy('updated_at', 'DESC')->paginate(10);
            $total = DB::table('commandes')->where('deleted_at', NULL)->where('user_id', Auth::user()->id)->whereDate('updated_at', '<', now()->subMonth())->whereIn('commandes.statut', ['livré', 'Retour en stock', 'Retour'])->count();
            //dd("salut");
        }


        foreach ($commandes as $commande) {
            if (!empty(User::withTrashed()->find($commande->user_id)))
                $users[] =  User::withTrashed()->find($commande->user_id);
        }
        //$commandes = Commande::all()->paginate(3) ;
        return view('commande.archive', [
            'nouveau' => $nouveau, 'commandes' => $commandes,
            'total' => $total,
            'users' => $users,
            'clients' => $clients,
            'livreurs' => $livreurs,
            'produits' => $produits,
            'villes' => $villes,
            'data' => $data
        ]);
    }




    public function filter(Request $request)
    {

        $commandes = DB::table('commandes')->where('commandes.deleted_at', NULL);
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


        $users = [];
        $produits = [];
        $data = $request->all();
        if (!Gate::denies('ecom')) {
            $produits = Produit::where('user_id', Auth::user()->id)->get();
            //dd($produits);
        }

        if (Gate::denies('ramassage-commande')) { //session client donc on cherche seulement dans ses propres commandes
            $commandes->where('user_id', Auth::user()->id);
            $clients = null;
            $livreurs = null;
        }

        if ($request->filled('statut')) {


            $commandes->where('statut', 'like', '%' . $request->statut . '%');

            //dd($commandes->count());
        } else {
            $commandes->whereIn('commandes.statut', ['livré', 'Retour en stock', 'Retour']);
        }

        if ($request->filled('client')) {
            $commandes->where('user_id', $request->client);
        }

        if ($request->filled('livreur')) {
            $livreur =  User::find($request->livreur);
            $userVilles = array_filter(explode(",",  $livreur->ville));


            $commandes->where(function ($query)  use ($userVilles) {
                $query->whereIn('commandes.ville', $userVilles)
                    ->whereNotIn('commandes.statut', ['envoyée', 'Ramassée', 'Recue']);
            });
        }

        if ($request->filled('nom')) {
            $commandes->where('nom', 'like', '%' . $request->nom . '%');
        }
        if ($request->filled('telephone')) {
            $commandes->where('telephone', 'like', '%' . $request->telephone . '%');
        }
        if ($request->filled('ville')) {
            if (!Gate::denies('livreur')) {
                $commandes->where('ville', Auth::user()->ville);
            } else {
                $commandes->where('ville',  $request->ville);
            }
        }

        if ($request->filled('dateMin')) {
            $commandes->whereDate('created_at', '>=', $request->dateMin);
        }
        if ($request->filled('dateMax')) {
            $commandes->whereDate('created_at', '<=', $request->dateMax);
        }
        if ($request->filled('prixMin') && $request->prixMin > 0) {
            $commandes->where('montant', '>=', $request->prixMin);
        }
        if ($request->filled('prixMax') && $request->prixMax > 0) {
            $commandes->where('montant', '<=', $request->prixMax);
        }

        if ($request->filled('bl')) {
            $commandes->where('traiter', '<>', 0)->where('facturer', 0);
        }

        if ($request->filled('facturer')) {
            $commandes->where('facturer', '<>', 0);
        }

        $total = $commandes->whereDate('updated_at', '<', now()->subMonth())->count();
        $commandes = $commandes->whereDate('updated_at', '<', now()->subMonth())->orderBy('updated_at', 'DESC')->paginate(10);
        foreach ($commandes as $commande) {
            if (!empty(User::withTrashed()->find($commande->user_id)))
                $users[] =  User::withTrashed()->find($commande->user_id);
        }


        return view('commande.archive', [
            'commandes' => $commandes,
            'nouveau' => $nouveau,
            'total' => $total,
            'users' => $users,
            'clients' => $clients,
            'produits' => $produits,
            'livreurs' => $livreurs,
            'villes' => $villes,
            'data' => $data

        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
}
