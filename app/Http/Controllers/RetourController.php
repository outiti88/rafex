<?php

namespace App\Http\Controllers;

use App\BonRetour;
use App\Commande;
use App\Http\Controllers\Controller;
use App\Produit;
use App\Retour;
use App\Statut;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetourController extends Controller
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

    public function index(){
        $users = [];
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

        $nouveau =  User::whereHas('roles', function($q){$q->whereIn('name', ['nouveau']);})->where('deleted_at',NULL)->count();

        $commandes = Commande::where('deleted_at', NULL)->where('isRetour', 1);

        if (!Gate::denies('ecom-client')) {
            $commandes = $commandes->where('user_id', Auth::user()->id);
        }
        else if (!Gate::denies('livreur')) {
            $commandes = $commandes->where('livreur', Auth::user()->id);
        }
        else if (!Gate::denies('superviseur')) {
            $commandes = $commandes->where('ville', Auth::user()->ville);
        }

        $total = $commandes->count();
        $commandes = $commandes->orderBy('updated_at', 'DESC')->paginate(20);

        foreach ($commandes as $commande) {
            if (!empty(User::withTrashed()->find($commande->user_id)))
                $users[] =  User::withTrashed()->find($commande->user_id);
        }

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
        }

        return view('retour.index', [
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


    public function getCommandesToAffect()
    {
        // Récupérer les commandes depuis la source de données
        $commandes = Commande::where('deleted_at', NULL)->where('isRetour', 1);

        if (!Gate::denies('ecom-client')) {
            $commandes = $commandes->where('user_id', Auth::user()->id);
        }
        else if (!Gate::denies('livreur')) {
            $commandes = $commandes->where('livreur', Auth::user()->id);
        }
        else if (!Gate::denies('superviseur')) {
            $commandes = $commandes->where('ville', Auth::user()->ville);
        }

        $commandeIds = [];
        $retours = Retour::Where('status', 'Prête à retourner')->get();
        foreach ($retours as $retour) {
                $commandeIds[] =  $retour->commande_id;
        }
        $commandes->whereIn('id',  $commandeIds);

        // Formatter les données dans le format attendu
        $formattedCommands = [];
        foreach ($commandes->get() as $command) {
            $formattedCommands[] = [
                'id' => $command->id,
                'numero' => $command->numero,
                'ville' => $command->ville,
                'vendeur' => $command->user()->first()->name
            ];
        }
        return response()->json($formattedCommands);
    }

    public function affectCommands(Request $request)
    {
        try{
            // Récupérer les données envoyées depuis la page HTML
            $selectedCommands = $request->input('selectedCommands');
            $commandes = Commande::whereIn('numero',$selectedCommands)->get();
            $livreurId = $request->input('livreurId');


            if (empty($selectedCommands) || empty($livreurId)) {
                throw new \Exception('Les commandes sélectionnées ou le livreur ne peuvent pas être vides');
            }
            if (!Gate::denies('superviseur')) {
                $city =  Auth::user()->ville;
            }
            else if (!Gate::denies('admin')){
                $city = $request->input('city') ?  $request->input('city') : 'Casablanca';
            }
            $currentDayName = date('l');

            $bonRetour = new BonRetour();
            $bonRetour->reference = 'R'.bin2hex(substr($currentDayName, -strlen($currentDayName), 3)) . date("mdis").count($selectedCommands);;
            $bonRetour->orders = count($selectedCommands);
            $bonRetour->livreur_id = $livreurId;
            $bonRetour->city = $city;
            $bonRetour->user()->associate(Auth::user())->save();

            $commandesIds = [];
            foreach ($commandes as $commande) {
                $commandesIds [] =$commande->id;
                $commande->livreur = $livreurId;
                $commande->save();
            }
            $retours = Retour::WhereIn('commande_id', $commandesIds)->get();
            foreach ($retours as $retour) {
                $retour->status = 'En Route vers le Propriétaire';
                $retour->bon_retour_id = $bonRetour->id;
                $retour->save();
            }

            // Retourner une réponse JSON
            return response()->json(['message' => 'Les commandes ont été affectées avec succès'], 200);

        } catch (\Exception $e) {
            // Attraper et gérer les erreurs
            $errorMessage = $e->getMessage();
            return response()->json(['error' => $errorMessage], 400);
        }
    }

    public function filter(Request $request)
    {
        $commandes = Commande::where('deleted_at', NULL)->where('isRetour', 1);

        if (!Gate::denies('ecom-client')) {
            $commandes = $commandes->where('user_id', Auth::user()->id);
        }
        else if (!Gate::denies('livreur')) {
            $commandes = $commandes->where('livreur', Auth::user()->id);
        }
        else if (!Gate::denies('superviseur')) {
            $commandes = $commandes->where('ville', Auth::user()->ville);
        }

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
        }

        if ($request->filled('statut')) {
            if($request->statut == 'Toutes Les Commandes'){
                return redirect()->route('retour.index');
            }
            else{
                $commandeIds = [];
                $retours = Retour::Where('status', $request->statut)->get();
                foreach ($retours as $retour) {
                        $commandeIds[] =  $retour->commande_id;
                }
                $commandes->whereIn('id',  $commandeIds);
            }
        }

        if ($request->filled('client')) {
            $commandes->where('user_id', $request->client);
        }

        if ($request->filled('livreur')) {
            $commandes->where('livreur', $request->livreur);
        }

        if ($request->filled('ville')) {
            if (!Gate::denies('livreur')) {
                $commandes->where('ville', Auth::user()->ville);
            } else {
                $commandes->where('ville',  $request->ville);
            }
        }


        $total = $commandes->count();
        $commandes = $commandes->orderBy('updated_at', 'DESC')->paginate(20);
        foreach ($commandes as $commande) {
            if (!empty(User::withTrashed()->find($commande->user_id)))
                $users[] =  User::withTrashed()->find($commande->user_id);
        }


        return view('retour.index', [
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
     * Store a newly created resource in storage.
     *
     */
    public function store($commandes)
    {
        $admin = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->first();

        foreach ($commandes as $commande) {
            $commande->isRetour = 1;
            $commande->livreur = null;
            $commande->save();

            $retour = new Retour();
            $retour->commande_id = $commande->id;
            $retour->last_status = $commande->statut;
            $retour->status = 'Nouveau';
            $retour->save();

            $statut = new Statut();
            $statut->commande_id = $retour->id;
            $statut->name = 'Nouveau';
            $statut->comment = 'Statut affecté automatiquement par le système';
            $statut->user()->associate($admin)->save();

            if($commande->ville === $commande->user()->first()->ville){
                $retour->status = 'Prête à retourner';
                $retour->save();

                $statut = new Statut();
                $statut->commande_id = $retour->id;
                $statut->name = 'Prête à retourner';
                $statut->comment = 'Statut affecté automatiquement par le système';
                $statut->user()->associate($admin)->save();
            }
            else if($commande->ville != $commande->user()->first()->ville && $commande->ville == 'Casablanca'){
                $retour->status = 'Arrivée au Hub Central';
                $retour->save();

                $statut = new Statut();
                $statut->commande_id = $retour->id;
                $statut->name = 'Arrivée au Hub Central';
                $statut->comment = 'Statut affecté automatiquement par le système';
                $statut->user()->associate($admin)->save();
            }
            else{
                $retour->status = 'Arrivée au Hub Régional';
                $retour->save();

                $statut = new Statut();
                $statut->commande_id = $retour->id;
                $statut->name = 'Arrivée au Hub Régional';
                $statut->comment = 'Statut affecté automatiquement par le système';
                $statut->user()->associate($admin)->save();
            }
        }
        return count($commandes);
    }

}
