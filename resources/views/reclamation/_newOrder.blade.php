<div class="card-body" style="font-size: 9px">
    <div class="row">
        <div class="col-6">
            <span style="font-weight: bold">Nom du client : </span>{{$commande->nom}}
        </div>
        <div class="col-6">
            <span style="font-weight: bold">Téléphone : </span>{{$commande->telephone}}
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <span style="font-weight: bold">Adresse: </span>{{$commande->adresse    }}
        </div>
        <div class="col-6">
            <span style="font-weight: bold">Montant : </span>{{$commande->montant}}
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <span style="font-weight: bold">Colis fragile: </span><input type="checkbox" disabled @if ($commande->is_fragile == 1) checked @endif>
        </div>
        <div class="col-6">
            <span style="font-weight: bold">Posibilité d'ouverture du colis : </span><input type="checkbox" disabled @if ($commande->isOpen == 1) checked @endif>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <span style="font-weight: bold">Note : </span>{{$commande->note}}
        </div>
    </div>

</div>
