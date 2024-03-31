<div class="accordion" id="accordionExample">
    <div class="card">
      <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <h2 class="mb-0">
          <button class="btn" type="button" >
            Informations de la nouvelle Commande
          </button>
        </h2>
      </div>

      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
        @if($reclamation->etat == 0)
            @include('reclamation._oldOrder')
        @else
            @include('reclamation._newOrder')
        @endif
      </div>
    </div>
    <div class="card">
      <div class="card-header" id="headingTwo"  data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        <h2 class="mb-0">
          <button class="btn collapsed" type="button">
            Informations de l'ancien Commande
          </button>
        </h2>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
        @if($reclamation->etat == 0)
            @include('reclamation._newOrder')
        @else
            @include('reclamation._oldOrder')
        @endif
      </div>
    </div>
  </div>
