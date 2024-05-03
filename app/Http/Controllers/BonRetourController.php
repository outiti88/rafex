<?php

namespace App\Http\Controllers;

use App\BonRetour;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BonRetourController extends Controller
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
        $total = 0;
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $data = [];

        if (!Gate::denies('admin')) {
            //session administrateur donc on affiche tous les demandes de transfers
            $total = DB::table('bon_retours')->count();
            $bon_retours = DB::table('bon_retours')->orderBy('created_at', 'DESC')->paginate(10);
        }
        else if (!Gate::denies('superviseur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            $total = DB::table('bon_retours')->where('user_id', Auth::user()->id)->orWhere('city', Auth::user()->ville)->count();
            $bon_retours = DB::table('bon_retours')->where('user_id', Auth::user()->id)->orWhere('city', Auth::user()->ville)->orderBy('created_at', 'DESC')->paginate(10);
        }
        else if (!Gate::denies('livreur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            $total = DB::table('bon_retours')->where('livreur_id', Auth::user()->id)->count();
            $bon_retours = DB::table('bon_retours')->where('livreur_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('retour.bonretour.index', [
            'data' => $data ,
            'bon_retours' => $bon_retours,
            'nouveau' => $nouveau,
            'total' => $total,
        ]);
    }


    /* Display the specified resource.
     *
     * @param  \App\BonRetour  $bonretour
     * @return \Illuminate\Http\Response
     */
    public function show(BonRetour $bonretour)
    {
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        if (!Gate::denies('superviseur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            if($bonretour->user_id != Auth::user()->id && $bonretour->city !=  Auth::user()->ville){
                return abort(403, 'Unauthorized.');
            }
        }
        else if (!Gate::denies('livreur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            if($bonretour->livreur_id != Auth::user()->id){
                return abort(403, 'Unauthorized.');
            }
        }

        // Récupérer les retours liés au bon de retour
            $retours = $bonretour->retours()->get();

            // Initialisez un tableau pour stocker les commandes associées
            $commandesAssociees = [];

            // Parcourir les retours
            foreach ($retours as $retour) {
                // Récupérer la commande associée à chaque retour
                $commande = $retour->commande()->first();

                // Ajouter la commande au tableau des commandes associées
                $commandesAssociees[] = $commande;
            }
        return view('retour.bonretour.show', [
            'nouveau' => $nouveau, 'bonretour' => $bonretour,
            'commandes' => $commandesAssociees
        ]);
    }
}
