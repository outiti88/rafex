<?php

namespace App\Http\Controllers;


use App\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
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



    public function sendFacture(Request $request , $facture){
        $facture = DB::table('factures')->find($facture);
        $user = $facture->user_id;
        $user = DB::table('users')->find($user);
        //dd($user , $facture);
    $details =[
        'facture' => $facture ,
        'user' => $user
      ];
      \Mail::to($user->email)->send(new \App\Mail\PdfGenerate($details , "pdf"));
      $request->session()->flash('envoyer', $user->name);
      return redirect(route('facture.index'));
    }
}
