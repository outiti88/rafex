<?php

namespace App\Http\Controllers;

use App\Caisse;
use App\Commande;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaisseController extends Controller
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
        if (!Gate::denies('manage-users')) {

            $data = null;
            $clients = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['client', 'ecom']);
            })->get();
            $livreurs = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['livreur']);
            })->get();
            $nouveau =  User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['nouveau']);
            })->where('deleted_at', NULL)->count();

            $caisses = [];
            $livraison = [];
            $envoyers = [];

            foreach ($livreurs as $livreur) {
                $envoyer = DB::table('caisses')->select(DB::raw('sum(montant) as m'))->where('caisses.user_id', $livreur->id)->where('caisses.etat', 1)->first();
                $commandes = DB::table('commandes');

                $commandes->select(DB::raw('sum(montant) as m'))->where('commandes.livreur', $livreur->id)->where('commandes.statut', 'livré');

                $livreur = DB::table('commandes')->select(DB::raw('sum(livreurPart) as liv'))
                    ->where('commandes.livreur', $livreur->id)
                    ->whereIn('commandes.statut', ['livré', 'Annulée sur place']);

                $caisses[] = $commandes->get()[0]->m != null ? $commandes->get()[0]->m : 0;
                $livraison[] = $livreur->get()[0]->liv != null ? $livreur->get()[0]->liv : 0;
                $envoyers[] = $envoyer->m != null ? $envoyer->m : 0;
                //dd($commandes->get()[0]->m,$livreur);
            }
            //dd($caisses[0],$liraison[0],$livreurs[0]->name,$envoyers);

            return view('caisse.index', [
                'nouveau' => $nouveau,
                'clients' => $clients,
                'livreurs' => $livreurs,
                'data' => $data,
                'caisses' => $caisses,
                'livraison' => $livraison,
                'envoyers' => $envoyers,
            ]);
        } else {
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $commandes = DB::table('commandes')->where('deleted_at', null)->get();

        foreach ($commandes as $key => $commande) {
            $cmd = Commande::find($commande->id);

            if ($commande->ville != null) {
                $ville = DB::table('villes')->where('name', $commande->ville)->first();
                $cmd->livreurPart = $ville == null ? 0 : $ville->livreur;

                $livreurForCmd = User::where('ville', 'like', '%'.$commande->ville . ',%')->whereHas('roles', function ($q) {
                    $q->whereIn('name', ['livreur']);
                })->first();
                $cmd->livreur =   $livreurForCmd == null ? 1 : $livreurForCmd->id;

                $cmd->timestamps = false;
                $cmd->save();
            }
        }

        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $livreurId = (!Gate::denies('manage-users')) ? $request->livreur : Auth::user()->id;

        $caisse = new Caisse();
        $caisse->montant = $request->montant;
        $caisse->banque = $request->banque;
        $caisse->user_id = $livreurId;
        $caisse->etat =  (!Gate::denies('manage-users')) ? 1 : 0;
        $caisse->save();


        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::denies('manage-users') || Auth::user()->id == $id) {
            $nouveau =  User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['nouveau']);
            })->where('deleted_at', NULL)->count();
            $livreurObject =  User::findOrFail($id);
            $total = DB::table('caisses')->where('user_id', $id)->get()->count();
            $paiments = DB::table('caisses')->where('user_id', $id)->orderBy('updated_at', 'DESC')->paginate(5);


            $envoyer = DB::table('caisses')->select(DB::raw('sum(montant) as m'))->where('caisses.etat', 1)->where('user_id', $id)->first();
            $commandes = DB::table('commandes')->where('commandes.livreur', $id)->where('commandes.statut', 'livré');


            $commandes->select(DB::raw('sum(montant) as m'))->where('commandes.livreur', $id)->where('commandes.statut', 'livré');

            $livreur = DB::table('commandes')->select(DB::raw('sum(livreurPart) as liv'))
                ->where('commandes.livreur', $id)
                ->whereIn('commandes.statut', ['livré', 'Annulée sur place']);


            $caisse = $commandes->get()[0]->m != null ? $commandes->get()[0]->m : 0; //montant total en poche
            $livraison = $livreur->get()[0]->liv != null ? $livreur->get()[0]->liv : 0; //part livreur
            $envoyer = $envoyer->m != null ? $envoyer->m : 0; //caisse table

            return view('caisse.livreur', [
                'nouveau' => $nouveau, 'LivreurObject' => $livreurObject,
                'livreur' => $id,
                'caisses' => $caisse,
                'livraison' => $livraison,
                'envoyers' => $envoyer, 'paiments' => $paiments, 'data' => null, 'total' => $total
            ]);
        }

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::denies('manage-users')) {
            $paiment =  Caisse::findOrFail($id);
            $paiment->etat = 1;
            $paiment->save();
        }
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
