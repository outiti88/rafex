<?php

namespace App\Http\Controllers;

use App\BonLivraison;
use App\Commande;
use App\Http\Controllers\Controller;
use App\Ramassage;
use App\Statut;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RamassageController extends Controller
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

        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client']);
        })->orderBy('name')->get();
        $users = [];
        $commandes = [];
        $data = [];

        if (!Gate::denies('ramassage-commande')) {
            //session administrateur donc on affiche tous les commandes
            $total = DB::table('ramassages')->count();
            $ramassages = DB::table('ramassages')->orderBy('created_at', 'DESC')->paginate(10);
            foreach ($ramassages as $ramassage) {
                if (!empty(User::withTrashed()->find($ramassage->user_id)))
                    $users[] =  User::withTrashed()->find($ramassage->user_id);
            }
        } else {
            $ramassages = DB::table('ramassages')->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
            $total = DB::table('ramassages')->where('user_id', Auth::user()->id)->count();
            $commandes = Commande::where('commandes.deleted_at', NULL)->where('user_id', Auth::user()->id)->where('statut','envoyée')->get();
        }

        return view('ramassage.index', [
            'data' => $data ,
            'ramassages' => $ramassages,
            'commandes' => $commandes,
            'nouveau' => $nouveau,
            'total' => $total,
            'users' => $users,
            'clients' => $clients
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
        // dd($request);
        $fournisseur = Auth::user();

        // Get the current day name
        $currentDayName = date('l');

        $ramassage = new Ramassage();
        $commandesArray = explode(',', $request->commandes);
        $ramassage->description = $request->description == null ? '' : $request->description;
        $ramassage->phone = $request->phone;
        $ramassage->statut = 'En attente';
        $ramassage->city = $request->city;
        $ramassage->adress = $request->adress;
        $ramassage->number = count($commandesArray);

        $livreurForCmd = User::where('ville', 'like',  '%' . $request->city . ',%')->whereHas('roles', function ($q) {
            $q->whereIn('name', ['livreur']);
        })->first();

        $ramassage->livreurId = $livreurForCmd == null ? 1 : $livreurForCmd->id;
        $ramassage->prevu_at = $request->prevu_at;
        $ramassage->reference = bin2hex(substr($currentDayName, -strlen($currentDayName), 3)) . date("mdis");
        $ramassage->user()->associate($fournisseur)->save();

        $commandes = Commande::whereIn('numero',$commandesArray)->get();
        foreach ($commandes as $commande) {
            $commande->statut = 'En attente de ramassage';
            $commande->ramassage_id	= $ramassage->id;
            $commande->save();

            $statut = new Statut();
            $statut->commande_id = $commande->id;
            $statut->name = $commande->statut;
            $statut->user()->associate(Auth::user())->save();
            $commande->save();
        }

        $this->generateBonLivraison($commandesArray,$ramassage);

        $request->session()->flash('added', $ramassage->reference);

        $this->generateQrCode($ramassage->id);

        return redirect('/ramassage');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ramassage  $ramassage
     * @return \Illuminate\Http\Response
     */
    public function show(Ramassage $ramassage)
    {

        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        return view('ramassage.show', [
            'nouveau' => $nouveau, 'ramassage' => $ramassage,
            'commandes' => $ramassage->commandes()->get(),
        ]);
    }


    public function valide(Request $request, $id)
    {
        $ramassage = Ramassage::findOrFail($id);
        $this->editStatus($request, $ramassage);
        return redirect()->route('ramassage.show',[
            'ramassage' => $ramassage]);
    }


    public function scannedTicket(Request $request, $id){
        $ramassage = Ramassage::findOrFail($id);
        $this->editStatus($request, $ramassage);
        return redirect()->route('ramassage.show',[
            'ramassage' => $ramassage]);
    }

    public function editStatus(Request $request, Ramassage $ramassage){
        $isUpdate = false;
        if (!Gate::denies('ramassage-commande')) {
            if ($ramassage->statut == 'En attente') {
                $ramassage->statut = "Ramassé par le livreur";
                $isUpdate = true;
                $request->session()->flash('ramassage-validated', $ramassage->reference);
            }
            else if($ramassage->statut == 'Ramassé par le livreur' && !Gate::denies('manage-users')){
                $ramassage->statut = "Reçue";
                $isUpdate = true;
                $request->session()->flash('ramassage-validated', $ramassage->reference);
            }
            else if($ramassage->statut == 'Reçue' || Gate::denies('manage-users')){
                $request->session()->flash('ramassage-already-validated', $ramassage->reference);
            }

            if($isUpdate == true){
                $ramassage->save();
                $commandes = Commande::where('ramassage_id', $ramassage->id)->get();
                foreach ($commandes as $commande) {
                    $commande->statut = $ramassage->statut;
                    $commande->ramassage_id	= $ramassage->id;
                    $commande->save();

                    $statut = new Statut();
                    $statut->commande_id = $commande->id;
                    $statut->name = $commande->statut;
                    $statut->user()->associate(Auth::user())->save();
                    $commande->save();
                }
            }
        }
    }

    public function filter(Request $request)
    {

        $data = $request->all();
        $clients = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['client']);
        })->orderBy('name')->get();
        $ramassages = DB::table('ramassages')->orderBy('created_at', 'DESC');
        $users = [];
        $commandes = [];

        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();


        if ($request->filled('statut')) {
            $ramassages->where('statut', $request->statut);
        }

        if ($request->filled('city')) {
            $ramassages->where('city', $request->city);
        }

        if (!Gate::denies('ramassage-commande')) {
            if ($request->filled('client')) {
                $ramassages->where('user_id', $request->client);
            }
            $total = $ramassages->count();

            $ramassages = $ramassages->paginate(10);
            foreach ($ramassages as $reception) {
                if (!empty(User::withTrashed()->find($reception->user_id)))
                    $users[] =  User::withTrashed()->find($reception->user_id);
            }
        } else {
            $ramassages = $ramassages->where('user_id', Auth::user()->id)->paginate(10);
            $commandes = Commande::where('commandes.deleted_at', NULL)->where('user_id', Auth::user()->id)->where('statut','envoyée')->get();
            $total = DB::table('ramassages')->where('user_id', Auth::user()->id)->count();
        }


        return view('ramassage.index', [
            'data' => $data,
            'ramassages' => $ramassages,
            'commandes' => $commandes,
            'nouveau' => $nouveau,
            'total' => $total,
            'users' => $users,
            'clients' => $clients
        ]);
    }

    public function generateQrCode($id){
        $ramassage = Ramassage::findOrFail($id);
        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.url('/').'/ramassage/scanned/'.$ramassage->id);

        $imageContent = $response->body();

        $filename = 'qr_code_' . $ramassage->reference . '.png';

        // Save the decoded image to the public directory
        file_put_contents(public_path('uploads/ramassageQRCODE/' . $filename), $imageContent);
    }


    public function ticketsBuilder(Request $request, $id){

        $ramassage = Ramassage::findOrFail($id);
        // $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.url('/').'/ramassage/scanned/'.$ramassage->id);
        // // Get the content of the response
        // // $imageContent = $response->getBody();
        // $imageContent = $response->body();
        // dd($imageContent);
        // Decode the base64-encoded image content
        // $decodedImage = base64_decode($imageContent);

        // Generate a unique filename for the decoded image
        $filename = 'qr_code_' . $ramassage->reference . '.png';

        // Save the decoded image to the public directory
        // file_put_contents(public_path('uploads/ramassageQRCODE/' . $filename), $imageContent);

        $pdf = app('dompdf.wrapper')->loadView('pdf.ramassage', ['ramassage' => $ramassage, 'qrImage' =>  $filename])->setPaper('A6');

        return $pdf->stream('ticket-ramassage.pdf');

    }

    public function getCommandesPerPages($commandes)
    {
        $commandesPerPages = [];

        $total = count($commandes) ;

        $m = ($total / 12);
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


    public function gen()
    {
        $pdf = App::make('dompdf.wrapper');
        $queryCommandes = Commande::where('deleted_at', NULL)->where('livreur', Auth::user()->id)
        ->whereIn('commandes.statut', ['en cours','Modifiée','Reporté','Relancée'])
        ->orderBy('updated_at', 'DESC');

        $commandes = $queryCommandes->get();
        $montant = $queryCommandes->sum('montant');

        $commandesPerPages = $this->getCommandesPerPages($commandes);
        $pdf = app('dompdf.wrapper')->loadView('pdf.delivery', ['livreur' => Auth::user(), 'commandesPerPages' => $commandesPerPages , 'total' => count($commandes) , 'montant' => $montant])->setPaper('A4');

        return $pdf->stream('Bon_de_distirbution.pdf');
    }


    public function generateBonLivraison($commandesIds, $ramassage){
        $user = Auth::user()->id;
        $commandes = DB::table('commandes')->whereIn('numero',$commandesIds);
        $bonLivraison = new BonLivraison();
        $bonLivraison->colis = $commandes->sum('colis');
        $bonLivraison->commande = $commandes->count();
        $bonLivraison->prix = $commandes->sum('prix');
        $bonLivraison->montant = $commandes->sum('montant');
        $bonLivraison->nonRammase = 0;
        $bonLivraison->ramassage()->associate($ramassage->id);
        $bonLivraison->user()->associate($user)->save();

        $commandes->update(array('traiter' => $bonLivraison->id));
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

}
