<?php

namespace App\Http\Controllers;

use App\Secteur;
use App\User;
use App\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VilleController extends Controller
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
        $villes= DB::table('villes')->orderBy('name')->get();
        $total= DB::table('villes')->orderBy('name')->count();
        $nouveau =  User::whereHas('roles', function($q){$q->whereIn('name', ['nouveau']);})->where('deleted_at',NULL)->count();


        return view('ville', ['nouveau'=>$nouveau,
                                'villes'=>$villes,
                                'total'=>$total
                                    ]);

    }

    public function getSecteur($id)
    {
        $ville = Ville::findOrFail($id);
        $secteurs= DB::table('secteurs')->where('ville_id',$id)->orderBy('name')->get();
        $total= DB::table('secteurs')->where('ville_id',$id)->orderBy('name')->count();
        $nouveau =  User::whereHas('roles', function($q){$q->whereIn('name', ['nouveau']);})->where('deleted_at',NULL)->count();


        return view('secteur', ['nouveau'=>$nouveau,
                                'secteurs'=>$secteurs,
                                'ville'=>$ville,
                                'total'=>$total
                                    ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ville = new Ville() ;
        $ville->name = $request->name;
        $ville->prix = $request->prix;
        $ville->prix_interne = $request->prix_interne;
        $ville->livreur = $request->livreur;
        $ville->refuse = $request->refuse;
        $ville->save();
        return back();
    }

    public function createSecteur(Request $request)
    {
        $secteur = new Secteur() ;
        $secteur->name = $request->name;
        $secteur->prix = $request->prix;
        $secteur->ville_id = $request->ville_id;
        $secteur->livreur = $request->livreur;
        $secteur->refuse = $request->refuse;
        $secteur->save();
        return back();
    }

    public function getSecteurs($villeId)
    {
        $secteurs = Secteur::where('ville_id', $villeId)->get();
        return response()->json($secteurs);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    public function updateVille(Request $request,$id){
        $ville = Ville::findOrFail($id);
        $ville->name = $request->name;
        $ville->prix = $request->prix;
        $ville->prix_interne = $request->prix_interne;
        $ville->livreur = $request->livreur;
        $ville->refuse = $request->refuse;
        $ville->save();
        return back();
    }

    public function updateSecteur(Request $request,$id){
        $secteur = Secteur::findOrFail($id);
        $secteur->name = $request->name;
        $secteur->prix = $request->prix;
        $secteur->livreur = $request->livreur;
        $secteur->refuse = $request->refuse;
        $secteur->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ville = Ville::findOrFail($id);

        \App\Ville::destroy($ville->id);

        return back();
    }

    public function destroySecteur($id)
    {
        $secteur = Secteur::findOrFail($id);

        \App\Secteur::destroy($secteur->id);

        return back();
    }
}
