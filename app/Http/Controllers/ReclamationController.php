<?php

namespace App\Http\Controllers;

use App\Commande;
use App\Reclamation;
use App\TicketComment;
use App\UpdatedCommande;
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
            $reclamations = $reclamations->where('user_id', Auth::user()->id)->orderBy('updated_at', 'DESC');
        }
        else{
            $reclamations = $reclamations->orderBy('updated_at', 'DESC');
        }

        $total = $reclamations->count();
        $reclamations = $reclamations->paginate(10);
        $commentTickets = array();
        $oldCommandes = [];
        foreach ($reclamations as $reclamation) {
            if (!Gate::denies('manage-users')) {
                $fournisseurs[] = DB::table('users')->where('id', $reclamation->user_id)->first();
            }
            $oldCommande = UpdatedCommande::where('reclamation_id', $reclamation->id)->first();
            if($oldCommande) $oldCommandes [$reclamation->id] = $oldCommande ;
            $commandes[] = DB::table('commandes')->where('id', $reclamation->commande_id)->first();
            $comments = TicketComment::where('reclamation_id', $reclamation->id)->get();
            $commentTickets[$reclamation->id] = $comments;
        }


        return view('reclamation.index', [
            'nouveau' => $nouveau,
            'reclamations' => $reclamations,
            'data' => $data,
            'fournisseurs' => $fournisseurs,
            'clients' => $clients,
            'commandes' => $commandes,
            'commentTickets' =>$commentTickets,
            'oldCommandes' =>$oldCommandes,
            'total' => $total
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
            $reclamation->number = 'T' . mt_rand(1000000000, 9999999999) . $request->commande_id;
            $reclamation->commande_id = $request->commande;
            $reclamation->etat =  0;
            $reclamation->objet =  $request->objet;
            $reclamation->description =  $request->description;
            $reclamation->save();

            $commentTicket = new TicketComment();
            $commentTicket->comment = ($request->objet == 'Modification de colis' && $request->description == null) ?  'Modification de colis' : $request->description;
            $commentTicket->reclamation_id = $reclamation->id;
            if ($request->hasfile('image')) {
                //dd($request->file('image'));
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension(); //getting image extension
                $filename = time() . '-ticket.' . $extension;
                $file->move('uploads/ticket/', $filename);
                $commentTicket->image = $filename;
            }

            if($request->objet == 'Modification de colis'){
                $updatedCommande = new UpdatedCommande();
                $updatedCommande->name = $request->nom;
                $updatedCommande->telephone = $request->telephone;
                $updatedCommande->adresse = $request->adresse;
                $updatedCommande->montant = ($request->mode == 'cd') ? $request->montant : 0;
                $updatedCommande->reclamation_id = $reclamation->id;
                $updatedCommande->note = $request->note;
                $updatedCommande->isOpen = ($request->isOpen) ? 1 : 0;
                $updatedCommande->is_fragile = ($request->isFragile) ? 1 : 0;
                $updatedCommande->save();
            }
            $commentTicket->user()->associate(Auth::user())->save();

            $request->session()->flash('ajouter', $reclamation->id);
        }

        return  back();
    }

    public function addComment(Request $request){

        $commentTicket = new TicketComment();
        $commentTicket->comment =  $request->description;
        if ($request->hasfile('image')) {
            //dd($request->file('image'));
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); //getting image extension
            $filename = time() . '-ticket.' . $extension;
            $file->move('uploads/ticket/', $filename);
            $commentTicket->image = $filename;
        }
        $commentTicket->reclamation_id = $request->reclamation;
        $commentTicket->user()->associate(Auth::user())->save();
        $request->session()->flash('commentAdded',$request->reclamation);
        return  back();
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
            if($reclamation->objet == 'Modification de colis'){
                $this->updateOldCommandeWithNewCommande($reclamation);
            }
            if ($reclamation->etat == 0) {
                $reclamation->etat = 1;
            }
            $reclamation->save();
            $request->session()->flash('traiter', $reclamation->id);
        }

        return back();
    }

    public function updateOldCommandeWithNewCommande($reclamation){
        $oldCommande = Commande::findOrFail($reclamation->commande_id);
        $newCommande = UpdatedCommande::where('reclamation_id',$reclamation->id)->first();
        if( $oldCommande && $newCommande){
            $prmt = clone($oldCommande);

            $oldCommande->nom = $newCommande->name;
            $oldCommande->telephone = $newCommande->telephone;
            $oldCommande->adresse = $newCommande->adresse;
            $oldCommande->montant = ($newCommande->mode == 'cd') ? $newCommande->montant : 0;
            $oldCommande->note = $newCommande->note;
            $oldCommande->isOpen = ($newCommande->isOpen) ? 1 : 0;
            $oldCommande->is_fragile = ($newCommande->isFragile) ? 1 : 0;
            $oldCommande->save();


            $newCommande->name = $prmt->nom;
            $newCommande->telephone = $prmt->telephone;
            $newCommande->adresse = $prmt->adresse;
            $newCommande->montant = ($prmt->mode == 'cd') ? $prmt->montant : 0;
            $newCommande->note = $prmt->note;
            $newCommande->isOpen = ($prmt->isOpen) ? 1 : 0;
            $newCommande->is_fragile = ($prmt->isFragile) ? 1 : 0;
            $newCommande->save();
        }
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
        if ($request->filled('objet')) {
            $reclamations = $reclamations->where('objet', $request->objet);
        }

        if ($request->filled('fournisseur') && !Gate::denies('manage-users')) {
            $reclamations = $reclamations->where('user_id', $request->fournisseur);
        }

        $total = $reclamations->count();

        $reclamations = $reclamations->orderBy('updated_at', 'DESC')->paginate(10);

        $commentTickets = array();
        foreach ($reclamations as $reclamation) {
            if (!Gate::denies('manage-users')) {
                $fournisseurs[] = DB::table('users')->where('id', $reclamation->user_id)->first();
            }
            $commandes[] = DB::table('commandes')->where('id', $reclamation->commande_id)->first();
            $comments = TicketComment::where('reclamation_id', $reclamation->id)->get();
            $commentTickets[$reclamation->id] = $comments;
        }
        return view('reclamation.index', [
            'nouveau' => $nouveau,
            'reclamations' => $reclamations,
            'data' => $data,
            'fournisseurs' => $fournisseurs,
            'clients' => $clients,
            'commandes' => $commandes,
            'commentTickets' =>$commentTickets,
            'total' => $total
        ]);
    }
}
