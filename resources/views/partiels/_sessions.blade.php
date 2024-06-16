@if (session()->has('traiter'))
<div class="alert alert-dismissible alert-success col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Succés !</strong> Le ticket a été bien fermé </a>.
</div>
@endif
@if (session()->has('commentAdded'))
<div class="alert alert-dismissible alert-success col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Succés !</strong> Commentaire ajouté pour le ticket </a>.
</div>
@endif
@if (session()->has('ajouter'))
<div class="alert alert-dismissible alert-success col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Succés !</strong> Votre Ticket a été bien ouvert </a>.
</div>
@endif
@if (session()->has('cmdRefuser'))
<div class="alert alert-dismissible alert-danger col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    Vous ne pouvez pas changer le statut en <b>"Retour en stock"</b>
    <br>
    <strong> Car: Le statut de la commande numéro {{$commande->numero}} est Annulée sur place et elle n'est pas encore facturée
        !</strong>
</div>
@endif
@if (session()->has('statut'))
<div class="alert alert-dismissible alert-info col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Succés !</strong> La commande a été bien Modifiée </a>.
</div>
@endif

@if (session()->has('edit'))
<div class="alert alert-dismissible alert-success col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Succés !</strong> Le statut de la commande numéro {{session()->get('edit')}} a été bien edité !
</div>
@endif
@if (session()->has('noedit'))
<div class="alert alert-dismissible alert-danger col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attention !</strong> Vous ne pouvez pas changer le statut de La commande numéro {{session()->get('noedit')}}
</div>
@endif
@if (session()->has('nodelete'))
<div class="alert alert-dismissible alert-danger col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attention !</strong>vous ne pouvez pas supprimer La commande numéro {{session()->get('nodelete')}}
</div>
@endif
@if (session()->has('noupdate'))
<div class="alert alert-dismissible alert-danger col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attention !</strong>vous ne pouvez pas modifier La commande numéro {{session()->get('noupdate')}} <br>
    Vous pouvez modifier que les commandes qui ont le statut <b>Nouvelle commande ou En attente de ramassage</b>
</div>
@endif
@if (session()->has('no-edit-invoiced'))
<div class="alert alert-dismissible alert-danger col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attention !</strong>vous ne pouvez pas modifier le statut de la commande numéro
    {{session()->get('no-edit-invoiced')}} <br>
    vous ne pouvez pas modifier les commandes qui ont été <b>facturées</b>
</div>
@endif
@if (session()->has('nonEncours'))
<div class="alert alert-dismissible alert-danger col-12">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Attention !</strong>vous ne pouvez pas changer le statut de La commande numéro
    {{session()->get('nonEncours')}} <br>
    vous pouvez modifier que les statuts des commandes qui ont le statut <b>En Cours</b>
</div>
@endif
