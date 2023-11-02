<?php

namespace App\Http\Controllers;

use App\Reclamation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReclamationController extends Controller
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
        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client', 'ecom']);
        })->orderBy('name')->get();
        $commandes = [];
        $fournisseurs = [];
        $data = null;
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $reclamations = DB::table('reclamations')->where('deleted_at', NULL);

        if (Gate::denies('manage-users')) {
            $reclamations = $reclamations->where('user_id', Auth::user()->id);
        }
        $reclamations = $reclamations->orderBy('updated_at', 'DESC')->paginate(10);

        foreach ($reclamations as $reclamation) {
            if (!Gate::denies('manage-users')) {
                $fournisseurs[] = DB::table('users')->where('id', $reclamation->user_id)->first();
            }
            $commandes[] = DB::table('commandes')->where('id', $reclamation->commande_id)->first();
        }

        return view('reclamation.index', [
            'nouveau' => $nouveau,
            'reclamations' => $reclamations,
            'data' => $data,
            'fournisseurs' => $fournisseurs,
            'clients' => $clients,
            'commandes' => $commandes
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

        if (!Gate::denies('fournisseur')) {
            $reclamation = new Reclamation();
            $reclamation->user_id = Auth::user()->id;
            $reclamation->commande_id = $request->commande;
            $reclamation->objet = $request->commande;
            $reclamation->etat =  0;
            $reclamation->objet =  $request->objet;
            $reclamation->description =  $request->description;
            $reclamation->save();
            $request->session()->flash('ajouter', $reclamation->objet);
        }

        return  redirect()->route('reclamation.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function traiter(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);
        if (!Gate::denies('manage-users')) {
            if ($reclamation->etat == 0) {
                $reclamation->etat = 1;
            }
            $reclamation->save();
            $request->session()->flash('traiter', $reclamation->objet);
        }

        return back();
    }

    public function filter(Request $request)
    {
        $commandes = [];
        $fournisseurs = [];
        $data = $request->all();
        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client', 'ecom']);
        })->orderBy('name')->get();

        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $reclamations = DB::table('reclamations')->where('deleted_at', NULL);

        if (Gate::denies('manage-users')) {
            $reclamations = $reclamations->where('user_id', Auth::user()->id);
        }

        if ($request->filled('etat')) {
            $reclamations = $reclamations->where('etat', $request->etat);
        }

        if ($request->filled('fournisseur') && !Gate::denies('manage-users')) {
            $reclamations = $reclamations->where('user_id', $request->fournisseur);
        }

        $reclamations = $reclamations->orderBy('updated_at', 'DESC')->paginate(10);

        foreach ($reclamations as $reclamation) {
            if (!Gate::denies('manage-users')) {
                $fournisseurs[] = DB::table('users')->where('id', $reclamation->user_id)->first();
            }
            $commandes[] = DB::table('commandes')->where('id', $reclamation->commande_id)->first();
        }

        return view('reclamation.index', [
            'nouveau' => $nouveau,
            'reclamations' => $reclamations,
            'data' => $data,
            'fournisseurs' => $fournisseurs,
            'clients' => $clients,
            'commandes' => $commandes
        ]);
    }
}
