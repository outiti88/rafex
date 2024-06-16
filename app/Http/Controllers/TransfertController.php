<?php

namespace App\Http\Controllers;

use App\Commande;
use App\Http\Controllers\Controller;
use App\Statut;
use App\Transfert;
use App\User;
use DateTime;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransfertController extends Controller
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

        $data = [];

        if (!Gate::denies('admin')) {
            //session administrateur donc on affiche tous les demandes de transfers
            $total = DB::table('transferts')->count();
            $transferts = DB::table('transferts')->orderBy('created_at', 'DESC')->paginate(10);
        }
        else if (!Gate::denies('superviseur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            $total = DB::table('transferts')->where('user_id', Auth::user()->id)->count();
            $transferts = DB::table('transferts')->where('user_id', Auth::user()->id)->orWhere('to_city', Auth::user()->ville)->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('transfert.index', [
            'data' => $data ,
            'transferts' => $transferts,
            'nouveau' => $nouveau,
            'total' => $total,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transfert  $transfert
     * @return \Illuminate\Http\Response
     */
    public function show(Transfert $transfert)
    {
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        if (!Gate::denies('superviseur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            if($transfert->user_id != Auth::user()->id && $transfert->to_city !=  Auth::user()->ville){
                return abort(403, 'Unauthorized.');
            }
        }
        return view('transfert.show', [
            'nouveau' => $nouveau, 'transfert' => $transfert,
            'commandes' => $transfert->commandes()->get()
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
        $currentUser = Auth::user();

        $fromCity = (!Gate::denies('superviseur')) ? Auth::user()->ville : $request->from_city;
        $toCity = ($fromCity == 'Casablanca') ? $request->to_city : 'Casablanca';
        // Get the current day name
        $currentDayName = date('l');

        $transfert = new Transfert();

        $orderNumbersArray = explode(",", $request->orderNumbersToAffect);
        $commandes = Commande::whereIn('numero',$orderNumbersArray)->get();
        foreach ($commandes as $commande) {
            // dd($fromCity == 'Casablanca' ,$commande->statut , $commande->statut != 'Reçue dans le HUB central', $commande->ville != $toCity , $commande->ville == 'Casablanca');
            // dd(($fromCity == 'Casablanca' && ($commande->statut != 'Reçue dans le HUB central' || $commande->ville != $toCity || $commande->ville == 'Casablanca')));

            if(($fromCity == 'Casablanca' && ($commande->statut != 'Reçue dans le HUB central' || $commande->ville != $toCity || $commande->ville == 'Casablanca')) || ( $fromCity != 'Casablanca' &&  ($commande->statut != 'Reçue dans le HUB régional' || $commande->ville == $fromCity))){
                $request->session()->flash('notAdded', 'Demande de transfert pas ajoutée - vérifie la commande avec le numéro :' .$commande->numero);
                return redirect('/transfert');
            }
        }
        if($commandes == null || count($commandes) == 0){
            return back()->with('erreur', 'Veuillez préciser les commandes à transferer');
        }

        $transfert->comment = $request->comment == null ? '' : $request->comment;
        if (!Gate::denies('admin')) {
            $transfert->from_city = $fromCity;
            $transfert->to_city = $toCity;
        }
        else{
            $transfert->from_city = $currentUser->ville;
            $transfert->to_city = $toCity;
        }
        $transfert->statut = 'Nouvelle';
        $transfert->sent = count($commandes);
        $transfert->received = 0;
        $transfert->reference = bin2hex(substr($currentDayName, -strlen($currentDayName), 3)) . date("mdis");
        $transfert->user()->associate($currentUser)->save();


        foreach ($commandes as $commande) {
            $commande->statut = 'Prêt à transférer vers '.$toCity;
            $commande->transfert_id	= $transfert->id;
            $commande->save();

            $statut = new Statut();
            $statut->commande_id = $commande->id;
            $statut->name = $commande->statut;
            $statut->user()->associate(Auth::user())->save();
        }

        $this->generateQrCode($transfert->id);
        $request->session()->flash('added', $transfert->reference);
        return redirect('/transfert');

    }

    public function editStatus(Request $request, Transfert $transfert){
        $isUpdate = false;
        if (!Gate::denies('admin-superviseur')) {
            if ($transfert->statut == "Nouvelle" && (!Gate::denies('admin') ||  ( !Gate::denies('superviseur') && $transfert->user_id ==  Auth::user()->id ) )) {
                $transfert->statut = "Envoyée";
                $isUpdate = true;
                $request->session()->flash('transfert-sent', $transfert->reference);
            }
            else if($transfert->statut == "Envoyée" && (!Gate::denies('admin') ||  ( !Gate::denies('superviseur') && $transfert->to_city ==  Auth::user()->ville ) )){
                $transfert->statut = "Reçue";
                $isUpdate = true;
                $request->session()->flash('transfert-received', $transfert->reference);
            }
            else{
                return abort(403, 'Unauthorized.');
            }

            if($isUpdate == true){
                $transfert->comment = $request->comment;
                $transfert->received = $transfert->sent;
                if($transfert->statut == "Reçue"){
                    $transfert->validate_at = new DateTime('now');
                    $transfert->validate_by = Auth::user()->id;
                }
                $transfert->save();

                $commandes = Commande::where('transfert_id', $transfert->id)->get();

                foreach ($commandes as $commande) {
                    if($transfert->statut == "Envoyée"){
                        if($transfert->to_city == 'Casablanca'){
                            $commande->statut = 'Envoyée vers le hub central';
                        }
                        else{
                            $commande->statut = 'Envoyée vers le hub régional';
                        }
                    }
                    if($transfert->statut == "Reçue"){
                        if($transfert->to_city == $commande->ville){
                            $commande->statut = 'Prête à livrer';
                        }
                        else{
                            $commande->statut = 'Reçue dans le HUB central';
                        }
                    }
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

    public function valide(Request $request, $id)
    {
        $transfert = Transfert::findOrFail($id);
        $this->editStatus($request, $transfert);
        return redirect()->route('transfert.show',[
            'transfert' => $transfert]);
    }


    public function scannedTicket(Request $request, $id){
        $transfert = Transfert::findOrFail($id);
        $this->editStatus($request, $transfert);
        return redirect()->route('transfert.show',[
            'transfert' => $transfert]);
    }

    public function generateQrCode($id){
        $transfert = Transfert::findOrFail($id);
        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.url('/').'/transfert/scanned/'.$transfert->id);

        $imageContent = $response->body();

        $filename = 'qr_code_' . $transfert->reference . '.png';

        // Save the decoded image to the public directory
        file_put_contents(public_path('uploads/transfertQRCODE/' . $filename), $imageContent);
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


    public function gen($id)
    {
        $pdf = App::make('dompdf.wrapper');

        $queryCommandes = Commande::where('deleted_at', NULL)->where('transfert_id',$id)->orderBy('updated_at', 'DESC');

        $transfert = Transfert::findOrFail($id);
        $commandes = $queryCommandes->get();
        $montant = $queryCommandes->sum('montant');

        $commandesPerPages = $this->getCommandesPerPages($commandes);
        $pdf = app('dompdf.wrapper')->loadView('pdf.transfert', ['transfert' => $transfert, 'commandesPerPages' => $commandesPerPages , 'total' => count($commandes)])->setPaper('A4');

        return $pdf->stream('bon_de_transfert.pdf');
    }


    public function filter(Request $request)
    {
        $data = $request->all();

        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        $transferts = DB::table('transferts')->orderBy('created_at', 'DESC');

        if ($request->filled('to_city')) {
            $transferts->where('to_city', $request->to_city);
        }
        if ($request->filled('from_city')) {
            $transferts->where('from_city', $request->from_city);
        }
        if ($request->filled('statut')) {
            $transferts->where('statut', $request->statut);
        }

        if (!Gate::denies('admin')) {
            //session administrateur donc on affiche tous les demandes de transfers
            $total = $transferts->count();
            $transferts = $transferts->paginate(10);
        }
        else if (!Gate::denies('superviseur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            $total = $transferts ->where('user_id', Auth::user()->id)->count();
            $transferts = $transferts ->where('user_id', Auth::user()->id)->paginate(10);
        }

        return view('transfert.index', [
            'data' => $data ,
            'transferts' => $transferts,
            'nouveau' => $nouveau,
            'total' => $total,
        ]);
    }
}
