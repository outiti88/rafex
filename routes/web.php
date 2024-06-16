<?php

use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::get('/', 'DashboardController@dash')->name('dashboard');

Route::post('/stock/{id}/inventaire', 'StockController@corriger')->name('stock.corriger')->middleware('can:manage-users');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/terms', 'TermsController@index')->name('terms');

Route::get('/BonCommande/pdf', 'BonCommandeController@gen')->name('bonCommande.index')->middleware('can:manage-users');

Route::get('/facture/{id}/send', 'EmailController@sendFacture')->name('email.facture')->middleware('can:manage-users');


/* ------------------------------------------ RELANCE ---------------------------------------------------------------------*/
Route::post('/commandes/{id}/relance', 'RelanceController@relancer')->name('relance.relancer')->middleware('can:ramassage-commande');
Route::resource('/Relance', 'RelanceController')->only([
    'index', 'edit'
    ])->middleware('can:manage-users');

/* ------------------------------------------ COMMANDE ---------------------------------------------------------------------*/
Route::get('/tickets/pdf', 'CommandeController@ticketsBuilder')->name('ticket.index')->middleware('can:valide');
Route::get('showFromNotify/{commande}/{notification}', 'CommandeController@showFromNotify')->name('commandes.showFromNotify')->middleware('can:valide');
Route::get('/commandes/{id}/statut', 'CommandeController@changeStatut')->name('commandeStatut')->middleware('can:ramassage-commande');
Route::get('/commandes/{commandeId}/relanceByClient', 'CommandeController@relanceCommandeByClient')->name('commandes.relance')->middleware('can:fournisseur');
Route::patch('/commandes/{id}/statut', 'CommandeController@statutAdmin')->name('statut.admin')->middleware('can:ramassage-commande');
Route::get('/commandes/{id}/valide', 'CommandeController@retourStock')->name('commande.valideRetour')->middleware('can:manage-users');
Route::get('pdf/{id}/A6', 'CommandeController@gen')->name('pdf.gen')->middleware('can:valide');
Route::get('pdf/{id}/A8', 'CommandeController@genA8')->name('pdf.genA8')->middleware('can:valide');
Route::get('export', 'CommandeController@export')->name('export');
Route::post('import', 'CommandeController@import')->name('import');
Route::get('/search', 'CommandeController@search')->name('commande.search')->middleware('can:valide');
Route::get('/commandes/filter', 'CommandeController@filter')->name('commande.filter')->middleware('can:valide');
Route::patch('/commandes/{id}/livreur', 'CommandeController@affecterLivreur')->name('commande.livreur')->middleware('can:admin-superviseur-personnel');
Route::get('/order/status/update', 'CommandeController@updateSatuts')->name('commande.statut.update')->middleware('can:manage-users');
Route::get('/order/status/expedier', 'CommandeController@expedier')->name('commande.expedier')->middleware('can:admin-superviseur-personnel');
Route::get('/order/status/recevoir', 'CommandeController@recevoir')->name('commande.recevoir')->middleware('can:admin-superviseur-personnel');
Route::get('/order/status/affecterAuLivreur', 'CommandeController@affecterAuLivreur')->name('commande.affecterAuLivreur')->middleware('can:admin-superviseur-personnel');
Route::get('/order/{commande}/horszone', 'CommandeController@outRange')->name('commande.outRange')->middleware('can:manage-users');
Route::get('/order/{commande}/change', 'CommandeController@change')->name('commande.change')->middleware('can:client-admin');
Route::resource('/commandes', 'CommandeController')->except([
    'create', 'edit'
])->middleware('can:valide');

/* ------------------------------------------ USER ---------------------------------------------------------------------*/
Route::get('/user/new', 'Auth\RegisterController@nouveau')->name('user.nouveau');
Route::get('/user/register/{role}', 'Auth\RegisterController@new')->name('user.new')->middleware('can:edit-users');;
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function () {
    Route::resource('/users', 'UsersController', ['except' => ['show', 'create', 'store']])->middleware('can:valide');
});

/* ------------------------------------------ PROFIL ---------------------------------------------------------------------*/
Route::get('/profil', 'ProfilController@index')->name('profil.index')->middleware('can:valide');
Route::match(['put', 'patch'], '/profil/{user}', 'ProfilController@update')->name('profil.update')->middleware('can:valide');

/* ------------------------------------------ TRACKING ---------------------------------------------------------------------*/
Route::get('/tracking/numero', 'TrackingController@index')->name('tracking.index');
Route::get('/tracking', 'TrackingController@search')->name('tracking.search');

/* ------------------------------------------ ARCHIVE ---------------------------------------------------------------------*/
Route::get('/archive', 'ArchiveController@index')->name('archive.index')->middleware('can:delete-commande');
Route::get('/archive/filter', 'ArchiveController@filter')->name('archive.filter')->middleware('can:valide');

/* ------------------------------------------ VILLE ---------------------------------------------------------------------*/
Route::put('/ville/{id}', 'VilleController@updateVille')->name('ville.updateVille')->middleware('can:edit-users');
Route::resource('/ville', 'VilleController')->middleware('can:edit-users');

Route::get('/secteur/{id}', 'VilleController@getSecteur')->name('ville.getSecteur')->middleware('can:edit-users');
Route::put('/secteur/{id}', 'VilleController@updateSecteur')->name('ville.updateSecteur')->middleware('can:edit-users');
Route::delete('/secteur/{id}', 'VilleController@destroySecteur')->name('ville.destroySecteur')->middleware('can:edit-users');
Route::post('/secteur', 'VilleController@createSecteur')->name('ville.createSecteur')->middleware('can:edit-users');
Route::get('/secteurs/{ville}', 'VilleController@getSecteurs')->name('ville.getSecteurs');

/* ------------------------------------------ PRODUIT ---------------------------------------------------------------------*/
Route::get('/stock/filter', 'ProduitController@filter')->name('stock.filter')->middleware('can:gestion-stock');
Route::resource('/produit', 'ProduitController')->except([
    'create', 'edit'
])->middleware('can:gestion-stock');

/* ------------------------------------------ CAISSE ---------------------------------------------------------------------*/
Route::get('/caisse', 'CaisseController@index')->name('caisse.index')->middleware('can:edit-users');
Route::get('/caisse/{id}', 'CaisseController@show')->name('caisse.livreur')->middleware('can:livreur-admin');
Route::get('/caisse/{id}/valide', 'CaisseController@edit')->name('caisse.edit')->middleware('can:edit-users');
Route::get('/commande/caisse/commande', 'CaisseController@create')->name('caisse.create')->middleware('can:edit-users');
Route::post('/caisse', 'CaisseController@store')->name('caisse.store')->middleware('can:livreur-admin');

/* ------------------------------------------ TRANSFERT ---------------------------------------------------------------------*/
Route::resource('/transfert', 'TransfertController')->except([
    'create', 'edit'
    ])->middleware('can:admin-superviseur');
Route::get('/transfert/{id}/validate', 'TransfertController@valide')->name('transfert.validate')->middleware('can:admin-superviseur');
Route::get('/transferts/filter', 'TransfertController@filter')->name('transfert.filter')->middleware('can:admin-superviseur');;
Route::get('/transfert/{id}/pdf', 'TransfertController@gen')->name('transfert.pdf')->middleware('can:admin-superviseur');
Route::get('/transfert/scanned/{id}', 'TransfertController@scannedTicket')->name('transfert.scannedTicket')->middleware('can:admin-superviseur');

/* ------------------------------------------ TRANSFERT DES RETOURS ---------------------------------------------------------------------*/

Route::resource('/transfert-retour', 'TransfertRetourController')->except(['create', 'edit'])->middleware('can:admin-superviseur')->names([
    'index' => 'transfert.retour.index',
    'store' => 'transfert.retour.store',
    'show' => 'transfert.retour.show',
    'update' => 'transfert.retour.update',
    'destroy' => 'transfert.retour.destroy',
]);

Route::get('/transfert-retour/{id}/validate', 'TransfertRetourController@valide')->name('transfert.retour.validate')->middleware('can:admin-superviseur');
Route::get('/transfert-retours/filter', 'TransfertRetourController@filter')->name('transfert.retour.filter')->middleware('can:admin-superviseur');;
Route::get('/transfert-retour/{id}/pdf', 'TransfertRetourController@gen')->name('transfert.retour.pdf')->middleware('can:admin-superviseur');
Route::get('/transfert-retour/scanned/{id}', 'TransfertRetourController@scannedTicket')->name('transfert.retour.scannedTicket')->middleware('can:admin-superviseur');

/* ------------------------------------------ RECLAMATION ---------------------------------------------------------------------*/
Route::post('/reclamation', 'ReclamationController@store')->name('reclamation.store')->middleware('can:fournisseur');
Route::post('/reclamation/comment', 'ReclamationController@addComment')->name('reclamation.addComment')->middleware('can:delete-commande');
Route::get('/reclamation', 'ReclamationController@index')->name('reclamation.index')->middleware('can:delete-commande');
Route::delete('/reclamation', 'ReclamationController@destroy')->name('reclamation.destroy')->middleware('can:delete-commande');
Route::get('/reclamation/{id}', 'ReclamationController@traiter')->name('reclamation.traiter')->middleware('can:manage-users');
Route::get('/filter/reclamation', 'ReclamationController@filter')->name('reclamation.filter')->middleware('can:delete-commande');;

/* ------------------------------------------ RAMASSAGE ---------------------------------------------------------------------*/
Route::resource('/ramassage', 'RamassageController')->except([
    'create', 'edit'
])->middleware('can:gestion-ramassage');
Route::patch('/ramassage/{id}/livreur', 'RamassageController@affecterLivreur')->name('ramassage.livreur')->middleware('can:admin-superviseur');
Route::get('/ramassage/{id}/validate', 'RamassageController@valide')->name('ramassage.validate')->middleware('can:ramassage-commande');
Route::get('/ramassages/filter', 'RamassageController@filter')->name('ramassage.filter');
Route::get('/distribution/pdf', 'RamassageController@gen')->name('distirbution.gen')->middleware('can:livreur-admin');
Route::get('/ramassage/scanned/{id}', 'RamassageController@scannedTicket')->name('ramassage.scannedTicket')->middleware('can:valide');
// Route::get('/ramassage/{id}/tickets/pdf', 'RamassageController@ticketsBuilder')->name('ramassage.ticket')->middleware('can:valide');

/* ------------------------------------------ RECEPTION ---------------------------------------------------------------------*/
Route::resource('/reception', 'ReceptionController')->except([
    'create', 'edit'
    ])->middleware('can:gestion-stock');
Route::get('/reception/{id}/valide', 'ReceptionController@valide')->name('reception.valide')->middleware('can:manage-users');
Route::get('showFromNotify/{reception}/{notification}', 'ReceptionController@showFromNotify')->name('reception.showFromNotify')->middleware('can:valide');
Route::get('/receptions/filter', 'ReceptionController@filter')->name('reception.filter')->middleware('can:gestion-stock');

/* ------------------------------------------ BON LIVRAISON ---------------------------------------------------------------------*/
Route::resource('/bonlivraison', 'BonLivraisonController')->only([
    'index', 'store'
])->middleware('can:delete-commande');
Route::get('/bonlivraison/{id}/infos', 'BonLivraisonController@infos')->name('bon.infos')->middleware('can:valide');
Route::get('/bonlivraison/{id}/pdf', 'BonLivraisonController@gen')->name('bon.gen')->middleware('can:valide');
Route::get('/bonlivraison/{id}/details', 'BonLivraisonController@search')->name('bon.search')->middleware('can:valide');

/* ------------------------------------------ FACTURE ---------------------------------------------------------------------*/
Route::resource('/facture', 'FactureController')->only([
    'index', 'store'
])->middleware('can:client-admin');
Route::get('/facture/{id}/pdf', 'FactureController@gen')->name('facture.gen')->middleware('can:client-admin');
Route::get('/facture/{id}/details', 'FactureController@search')->name('facture.search')->middleware('can:client-admin');
Route::get('/facture/{id}/infos', 'FactureController@infos')->name('facture.infos')->middleware('can:client-admin');
Route::get('/facture/filter', 'FactureController@filter')->name('facture.filter')->middleware('can:valide');

/* ------------------------------------------ NOTIFICATION ---------------------------------------------------------------------*/
Route::get('/inbox', 'NotificationController@index')->name('inbox.index')->middleware('can:valide');
Route::get('/{notifications}/show', 'NotificationController@show')->name('inbox.show')->middleware('can:valide');
Route::get('/{notifications}/delete', 'NotificationController@destroy')->name('inbox.destroy')->middleware('can:valide');

/* ------------------------------------------ RETOUR ---------------------------------------------------------------------*/
Route::get('/retour', 'RetourController@index')->name('retour.index')->middleware('can:valide');
Route::get('/retour/commandes/toaffect', 'RetourController@getCommandesToAffect')->name('retour.toaffect')->middleware('can:admin-superviseur-personnel');
Route::post('/retour/commandes/affect', 'RetourController@affectCommands')->name('retour.toaffect')->middleware('can:admin-superviseur-personnel');
Route::get('/retour/filter', 'RetourController@filter')->name('retour.filter')->middleware('can:valide');

/* ------------------------------------------ BON RETOUR ---------------------------------------------------------------------*/
Route::resource('/bonretour', 'BonRetourController')->only([
    'index', 'show'
])->middleware('can:admin-superviseur-personnel-livreur');
