<?php

namespace App\Imports;

use App\Commande;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class CommandesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $fournisseur = Auth::user() ;
        $commande = new Commande();
        $commande->id = substr(uniqid(mt_rand(), 16), 0, 6);
        $commande->user_id = $fournisseur->id;
        $commande->telephone = $row['telephone'];
        $commande->ville = $row['ville'];
        $commande->secteur = $row['ville'];
        $commande->adresse = $row['adresse'];
        $commande->statut = "Nouvelle commande";
        $commande->colis = 1;
        $commande->poids = '';
        $commande->nom = $row['nom'];
        $commande->traiter = 0;
        $commande->facturer = 0;
        $commande->numero = substr($fournisseur->name, - strlen($fournisseur->name) , 2)."-".date("md-is")."-".substr(uniqid(mt_rand(), 16), 0, 4);
        $livreurForCmd = User::where('ville','like','%'.$commande->ville.'%')->whereHas('roles', function($q){$q->whereIn('name', ['livreur']);})->first();
        $commande->livreur = $livreurForCmd == null ? 1 : $livreurForCmd->id;
        //prixe de livraison de la commande
        if($fournisseur->prix === 0){
            $commande->prix = DB::table('villes')
                ->select('prix')
                ->where('name', $commande->ville)
                ->get()->first()->prix;

        }
        else{
            $commande->prix = $fournisseur->prix;
        }
        //la part du livreur
        $commande->livreur = DB::table('villes')
                ->select('livreur')
                ->where('name', $commande->ville)
                ->get()->first()->livreur;
        $ville= DB::table('villes')->where('name',$commande->ville)->first();
        $commande->livreurPart = $ville == null ? 15 : $ville->livreur;



        return $commande;
    }
}
