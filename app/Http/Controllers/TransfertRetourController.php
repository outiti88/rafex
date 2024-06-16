<?php

namespace App\Http\Controllers;

use App\Commande;
use App\Http\Controllers\Controller;
use App\Retour;
use App\Statut;
use App\TransfertRetour;
use App\User;
use DateTime;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransfertRetourController extends Controller
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
            $total = DB::table('transfert_retours')->count();
            $transfert_retours = DB::table('transfert_retours')->orderBy('created_at', 'DESC')->paginate(10);
        }
        else if (!Gate::denies('superviseur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            $total = DB::table('transfert_retours')->where('user_id', Auth::user()->id)->orWhere('to_city', Auth::user()->ville)->count();
            $transfert_retours = DB::table('transfert_retours')->where('user_id', Auth::user()->id)->orWhere('to_city', Auth::user()->ville)->orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('transfert.retour.index', [
            'data' => $data ,
            'transfert_retours' => $transfert_retours,
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
    public function show($id)
    {
        $transfert = TransfertRetour::findOrFail($id);
        $nouveau =  User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['nouveau']);
        })->where('deleted_at', NULL)->count();

        if (!Gate::denies('superviseur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            if($transfert->user_id != Auth::user()->id && $transfert->to_city !=  Auth::user()->ville){
                return abort(403, 'Unauthorized.');
            }
        }
        return view('transfert.retour.show', [
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

        $transfert = new TransfertRetour();

        $orderNumbersArray = explode(",", $request->orderNumbersToAffect);
        $commandes = Commande::whereIn('numero',$orderNumbersArray)->get();
        foreach ($commandes as $commande) {
            $retour = $commande->retours()->first();
            // dd($retour,$retour->status, $commande->retour != 'Arrivée au Hub Régional' , $commande->user()->first()->ville == $fromCity);
            if(($fromCity == 'Casablanca' && ($retour->status != 'Arrivée au Hub Central' || $commande->user()->first()->ville != $toCity || $commande->user()->first()->ville == 'Casablanca')) || ( $fromCity != 'Casablanca' &&  ($retour->status != 'Arrivée au Hub Régional' || $commande->user()->first()->ville == $fromCity))){
                $request->session()->flash('notAdded', 'Demande de transfert pas ajoutée - vérifie la commande avec le numéro :' .$commande->numero);
                return redirect('/transfert-retour');
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
        $transfert->statut = 'Nouveau';
        $transfert->sent = count($commandes);
        $transfert->received = 0;
        $transfert->reference = bin2hex(substr($currentDayName, -strlen($currentDayName), 3)) . date("mdis");
        $transfert->user()->associate($currentUser)->save();


        foreach ($commandes as $commande) {
            $retour = $commande->retours()->first();
            $retour->status = 'Prêt à transférer vers '.$toCity;
            $retour->save();

            $commande->transfert_retour_id	= $transfert->id;
            $commande->save();

            $statut = new Statut();
            $statut->commande_id = $retour->id;
            $statut->name = $retour->status;
            $statut->user()->associate(Auth::user())->save();
        }

        $this->generateQrCode($transfert->id);
        $request->session()->flash('added', $transfert->reference);
        return redirect('/transfert-retour');

    }

    public function editStatus(Request $request, TransfertRetour $transfert){
        $isUpdate = false;
        if (!Gate::denies('admin-superviseur')) {
            if ($transfert->statut == "Nouveau" && (!Gate::denies('admin') ||  ( !Gate::denies('superviseur') && $transfert->user_id ==  Auth::user()->id ) )) {
                $transfert->statut = "Envoyé";
                $isUpdate = true;
                $request->session()->flash('transfert-sent', $transfert->reference);
            }
            else if($transfert->statut == "Envoyé" && (!Gate::denies('admin') ||  ( !Gate::denies('superviseur') && $transfert->to_city ==  Auth::user()->ville ) )){
                $transfert->statut = "Reçu";
                $isUpdate = true;
                $request->session()->flash('transfert-received', $transfert->reference);
            }
            else{
                return abort(403, 'Unauthorized.');
            }

            if($isUpdate == true){
                $transfert->comment = $request->comment;
                $transfert->received = $transfert->sent;
                if($transfert->statut == "Reçu"){
                    $transfert->validate_at = new DateTime('now');
                    $transfert->validate_by = Auth::user()->id;
                }
                $transfert->save();

                $commandes = Commande::where('transfert_retour_id', $transfert->id)->get();

                foreach ($commandes as $commande) {
                    $retour = $commande->retours()->first();

                    if($transfert->statut == "Envoyé"){
                        if($transfert->to_city == 'Casablanca'){
                            $retour->status = 'En Route vers le Hub Central';
                        }
                        else{
                            $retour->status = 'En Route vers le Hub Régional';
                        }
                    }
                    if($transfert->statut == "Reçu"){
                        if($transfert->to_city == $commande->user()->first()->ville){
                            $retour->status = 'Prête à retourner';
                        }
                        else{
                            $retour->status = 'Arrivée au Hub Central';
                        }
                    }
                    $retour->save();

                    $statut = new Statut();
                    $statut->commande_id = $retour->id;
                    $statut->name = $retour->status;
                    $statut->user()->associate(Auth::user())->save();
                    $retour->save();
                }
            }
        }
    }

    public function valide(Request $request, $id)
    {
        $transfert = TransfertRetour::findOrFail($id);
        $this->editStatus($request, $transfert);
        return redirect()->route('transfert.retour.show',[
            'id' => $transfert->id]);
    }


    public function scannedTicket(Request $request, $id){
        $transfert = TransfertRetour::findOrFail($id);
        $this->editStatus($request, $transfert);
        return redirect()->route('transfert.retour.show',[
            'id' => $transfert->id]);
    }

    public function generateQrCode($id){
        $transfert = TransfertRetour::findOrFail($id);
        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.url('/').'/transfert-retour/scanned/'.$transfert->id);

        $imageContent = $response->body();

        $filename = 'qr_code_' . $transfert->reference . '.png';

        // Save the decoded image to the public directory
        file_put_contents(public_path('uploads/transfert-retourQRCODE/' . $filename), $imageContent);
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

        $queryCommandes = Commande::where('deleted_at', NULL)->where('transfert_retour_id',$id)->orderBy('updated_at', 'DESC');

        $transfert = TransfertRetour::findOrFail($id);
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

        $transfert_retours = DB::table('transfert_retours')->orderBy('created_at', 'DESC');

        if ($request->filled('to_city')) {
            $transfert_retours->where('to_city', $request->to_city);
        }
        if ($request->filled('from_city')) {
            $transfert_retours->where('from_city', $request->from_city);
        }
        if ($request->filled('statut')) {
            $transfert_retours->where('statut', $request->statut);
        }

        if (!Gate::denies('admin')) {
            //session administrateur donc on affiche tous les demandes de transfers
            $total = $transfert_retours->count();
            $transfert_retours = $transfert_retours->paginate(10);
        }
        else if (!Gate::denies('superviseur')) {
            //session superviseur donc on affiche que les demandes de transfers du superviseur
            $total = $transfert_retours ->where('user_id', Auth::user()->id)->count();
            $transfert_retours = $transfert_retours ->where('user_id', Auth::user()->id)->paginate(10);
        }

        return view('transfert.retour.index', [
            'data' => $data ,
            'transfert_retours' => $transfert_retours,
            'nouveau' => $nouveau,
            'total' => $total,
        ]);
    }
}
