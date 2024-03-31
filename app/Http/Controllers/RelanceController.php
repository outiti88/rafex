<?php

namespace App\Http\Controllers;

use App\Commande;
use App\Relance;
use App\Statut;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\DB;

class RelanceController extends Controller
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
        $nouveau =  User::whereHas('roles', function($q){$q->whereIn('name', ['nouveau']);})->where('deleted_at',NULL)->count();


        $all = DB::table('commandes')->where('commandes.deleted_at',NULL)->where('commandes.relance','>=','0')->where('commandes.statut','not like', "%Livré%")
            ->join('users', 'users.id', '=', 'commandes.user_id')
            ->select('commandes.*','users.image')
            ->whereNotNull('users.statut')
            ->orderBy('commandes.updated_at', 'DESC')->get();


        $vip =  DB::table('commandes')->where('commandes.deleted_at',NULL)->where('commandes.relance','0')->where('commandes.statut','not like', "%Livré%")
        ->join('users', 'users.id', '=', 'commandes.user_id')
        ->select('commandes.*','users.image')
        ->whereNotNull('users.statut')
        ->orderBy('commandes.updated_at', 'DESC')->get();


        $relance1 = DB::table('commandes')->where('commandes.deleted_at',NULL)->where('commandes.relance','1')->where('commandes.statut','not like', "%Livré%")
        ->join('users', 'users.id', '=', 'commandes.user_id')
        ->select('commandes.*','users.image')
        ->whereNotNull('users.statut')
        ->orderBy('commandes.updated_at', 'DESC')->get();



        $relance2 =  DB::table('commandes')->where('commandes.deleted_at',NULL)->where('commandes.relance','2')->where('commandes.statut','not like', "%Livré%")
        ->join('users', 'users.id', '=', 'commandes.user_id')
        ->select('commandes.*','users.image')
        ->whereNotNull('users.statut')
        ->orderBy('commandes.updated_at', 'DESC')->get();

        //dd($relance2);


        $relance3 =  DB::table('commandes')->where('commandes.deleted_at',NULL)->where('commandes.relance','3')->where('commandes.statut','not like', "%Livré%")
        ->join('users', 'users.id', '=', 'commandes.user_id')
        ->select('commandes.*','users.image')
            ->whereNotNull('users.statut')
            ->orderBy('commandes.updated_at', 'DESC')->get();


        return view('relance',['all'=>$all,'nouveau'=>$nouveau,
                                'vip'=>$vip ,
                                'relance1' => $relance1,
                                'relance2'=> $relance2,
                                'relance3' => $relance3
                                ]);
    }

    public function relancer(Request $request, $id){

        if(!Gate::denies('ramassage-commande')) {
            $commande = Commande::findOrFail($id);

            if($commande->relance >=0 && $commande->relance < 4){
                $statut = new Statut();
                $statut->commande_id = $commande->id;
                $statut->name = $request->statut;
                if($commande->relance == 4 && $request->statut != "Livré") $commande->statut = "Annulée";

                $statut->user()->associate(Auth::user())->save();
                $commande->statut = $request->statut;
                if($commande->statut === 'Relancée' || $commande->statut === 'Confirmé sous RDV'){
                    $commande->relance = 4;
                }
                else $commande->relance++;
                if($request->filled('prevu_at')) $commande->postponed_at = $request->prevu_at;
                    else $request->prevu_at = now() ;
                $commande->save();
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
        //
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
