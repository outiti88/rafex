<?php

namespace App\Http\Controllers;

use App\Commande;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class BonCommandeController extends Controller
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
    public function index(Request $request)
    {

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


    public function gen(Request $request)
    {
        $livreur = User::find($request->livreur);
        $queryCommandes = DB::table('commandes')->where('commandes.deleted_at', NULL)->whereIn('commandes.id', $request->item);

        $pdf = App::make('dompdf.wrapper');

        $commandes = $queryCommandes->get();
        $montant = $queryCommandes->sum('montant');

        $commandesPerPages = $this->getCommandesPerPages($commandes);
        $pdf = app('dompdf.wrapper')->loadView('pdf.delivery', ['livreur' => $livreur, 'commandesPerPages' => $commandesPerPages , 'total' => count($commandes) , 'montant' => $montant])->setPaper('A4');

        return $pdf->stream('Bon_de_distirbution.pdf');
    }



}
