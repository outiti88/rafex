<?php

namespace App\Console\Commands;

use App\Commande;
use App\Http\Controllers\RetourController;
use App\Retour;
use App\Statut;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateCommandStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:status-update';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update command status based on specific conditions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // $commandes = Commande::where('deleted_at', NULL)
        // ->where('isRetour', 0)
        // ->whereHas('statuts', function ($query) {
        //     $query->where('name', 'Injoignable')
        //         ->where('created_at', '<=', Carbon::now()->subDays(2));
        // })
        // ->get();

        $commandes = Commande::where('deleted_at',NULL)->where('isRetour', 0)
            ->where(function ($q) {
                $q->where('statut', 'Injoignable')
                ->where('updated_at', '<=', Carbon::now()->subDays(7));
            })
            ->orWhere(function ($q) {
                $q->whereIn('statut', ['Annulée', 'Refusée', 'Destination invalide', 'Pas de réponse', 'Annulée par téléphone', 'Colis endommagé', 'Annulée sur place', 'Colis endommagé', 'Numéro de téléphone erroné'])
                ->where('updated_at', '<=', Carbon::now()->subDays(2));
            })->get();

        // $commandes = Commande::where('deleted_at',NULL)->where('isRetour', 0)
        //     ->Where(function ($q) {
        //         $q->whereIn('statut', ['Annulée', 'Refusée','Injoignable', 'Destination invalide', 'Pas de réponse', 'Annulée par téléphone', 'Colis endommagé', 'Annulée sur place', 'Colis endommagé', 'Numéro de téléphone erroné']);
        //         // ->where('created_at', '<=', Carbon::now()->subDays(2));
        //     })->get();

        // Instanciation du contrôleur RetourController
        $retourController = new RetourController();

        // Appel de la méthode store() en passant la variable $commandes comme paramètre
        $numberOfCommandsUpdated = $retourController->store($commandes);

        $this->info($numberOfCommandsUpdated . ' Commands status updated successfully.');
    }
}
