<div class="card-body" style="font-size: 9px">
    <div class="row">
        <div class="col-6">
            <span style="font-weight: bold">Nom du client : </span>{{$oldCommandes[$reclamation->id]->name}}
        </div>
        <div class="col-6">
            <span style="font-weight: bold">Téléphone : </span>{{$oldCommandes[$reclamation->id]->telephone}}
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <span style="font-weight: bold">Adresse: </span>{{$oldCommandes[$reclamation->id]->adresse    }}
        </div>
        <div class="col-6">
            <span style="font-weight: bold">Montant : </span>{{$oldCommandes[$reclamation->id]->montant}}
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <span style="font-weight: bold">Colis fragile: </span><input type="checkbox" disabled @if ($oldCommandes[$reclamation->id]->is_fragile == 1) checked @endif>
        </div>
        <div class="col-6">
            <span style="font-weight: bold">Posibilité d'ouverture du colis : </span><input type="checkbox" disabled @if ($oldCommandes[$reclamation->id]->isOpen == 1) checked @endif>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <span style="font-weight: bold">Note : </span>{{$oldCommandes[$reclamation->id]->note}}
        </div>
    </div>

</div>
