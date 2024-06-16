@extends('racine')

@section('title')
   Gestion des Transferts des retours
@endsection


@section('style')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Gestion des demandes de transferts des retours</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Transfert des retours</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
        <div class="row float-right d-flex ">
            <div class="m-r-5" style="margin-right: 10px;">
                <a  class="btn btn-warning text-white"  data-toggle="modal" data-target="#modalTransfertSearch"><i class="fa fa-search"></i></a>
            </div>
            <div class="m-r-5">
                <a  class="btn btn-danger text-white"  data-toggle="modal" data-target="#modalAddTransfert"><i class="fa fa-plus-square"></i> Ajouter</a>
            </div>
            <div class="container my-4">
                <div class="modal fade" id="modalTransfertSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header text-center">
                                      <h4 class="modal-title w-100 font-weight-bold">Filtrer</h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body mx-3">
                                        <form class="form-horizontal form-material" method="GET" action="{{route('transfert.retour.filter')}}">
                                            @csrf
                                              <div class="form-group row">
                                                <label class="col-sm-4">Statut :</label>
                                                <div class="col-sm-8">
                                                    <select name="statut" class="form-control form-control-line" >
                                                        <option value="" disabled selected>Choisissez le Statut</option>
                                                        <option >Nouveau</option>
                                                        <option >Envoyé</option>
                                                        <option >Reçu</option>
                                                    </select>
                                                </div>
                                              </div>

                                              <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <label for="Expéditeur" class="col-sm-12">Expéditeur :</label>
                                                        <div class="col-sm-12">
                                                            <select name="from_city" class="form-control form-control-line" >
                                                                    <option checked value="">Choisissez la ville</option>
                                                                    @foreach (App\Ville::orderBy('name')->get() as $ville)
                                                                    <option value="{{$ville->name}}" class="rounded-circle">
                                                                        {{$ville->name}}
                                                                    </option>
                                                                    @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <label for="Destinataire" class="col-sm-12">Destinataire :</label>
                                                        <div class="col-sm-12">
                                                            <select name="to_city" class="form-control form-control-line">
                                                                    <option checked value="">Choisissez la ville</option>
                                                                    @foreach (App\Ville::orderBy('name')->get() as $ville)
                                                                    <option value="{{$ville->name}}" class="rounded-circle">
                                                                        {{$ville->name}}
                                                                    </option>
                                                                    @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-search"></i> Rechercher</button>

                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                  </div>
                                </div>
                </div>
            </div>

        </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        @if (session()->has('added'))
            <div class="alert alert-dismissible alert-success col-12">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                Demande de transfert envoyé avec la référence : <strong>{{session()->get('added')}} </strong>
          </div>
        @endif
        @if (session()->has('notAdded'))
            <div class="alert alert-dismissible alert-danger col-12">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{session()->get('notAdded')}}
          </div>
        @endif
           @if (session()->has('erreur'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{session()->get('erreur')}}
          </div>
        @endif
        <div class="collapse show" id="mycard-collapse" style="width : 100%;">
            <div class="card-body">
              <div class="row" style="display: flex;align-items: center;align-content: stretch;flex-wrap: wrap;justify-content: space-evenly">

                    <a  href="/transfert-retours/filter?statut=Nouveau" style="display:block ; margin: 0.5rem; font-size: 0.8em;padding: 1rem !important;color: white; cursor:pointer;margin-top:0.5rem" class="badge badge-secondary">
                      <span>Nouveau</span>
                    </a>
                    <a  href="/transfert-retours/filter?statut=Envoyé" style="display:block ; margin: 0.5rem; font-size: 0.8em;padding: 1rem !important;color: white; cursor:pointer;margin-top:0.5rem" class="badge badge-success">
                      <span>Envoyé</span>
                    </a>
                    <a  href="/transfert-retours/filter?statut=Reçu" style="display:block ; margin: 0.5rem; font-size: 0.8em;padding: 1rem !important;color: white; cursor:pointer;margin-top:0.5rem" class="badge badge-primary cielBadge">
                      <span>Reçu</span>
                    </a>

              </div>
            </div>
          </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gérer vos demandes de transfert des retours</h4>
                    <h6 class="card-subtitle">Nombre total des demandes : <code>{{$total}} Demandes</code> .</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" style="font-size: 0.85em;">
                        <thead>
                            <tr>
                                <th scope="col">Réference</th>
                                <th scope="col">Expéditeur</th>
                                <th scope="col">Colis envoyés</th>
                                <th scope="col">Date d'ajout</th>
                                <th scope="col">Destinataire</th>
                                <th scope="col">Date de validation</th>
                                <th scope="col">Colis réceptionnés</th>
                                <th scope="col">Créée Par</th>
                                <th scope="col">Validée Par</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Détail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transfert_retours as $index => $transfert_retour)
                                <tr>
                                    <td>{{$transfert_retour->reference}}</td>
                                    <td>
                                        @if ($transfert_retour->to_city == 'Casablanca')
                                            hub Central de
                                        @else
                                            hub régional de
                                        @endif
                                        {{$transfert_retour->from_city}}
                                    </td>
                                    <td>{{$transfert_retour->sent}}</td>
                                    <td>{{$transfert_retour->created_at}}</td>
                                    <td>
                                        @if ($transfert_retour->to_city == 'Casablanca')
                                            hub Central de
                                        @else
                                            hub régional de
                                        @endif
                                        {{$transfert_retour->to_city}}
                                    </td>
                                    <td>
                                        {{$transfert_retour->validate_at}}</td>
                                    <td>{{$transfert_retour->received}}</td>
                                    <td>{{App\User::where('id',$transfert_retour->user_id)->first()->name}}</td>
                                    <td>
                                        @if ($transfert_retour->validate_by != null)
                                            {{App\User::where('id',$transfert_retour->validate_by)->first()->name}}
                                        @else
                                            Pas encore validée
                                        @endif
                                    </td>

                                    <td>
                                        <span style="color : white"       @switch($transfert_retour->statut)
                                            @case("Nouveau")
                                            class="badge-pill badge badge-secondary"
                                            @break
                                            @case("Envoyé") class="badge badge-pill badge-success" @break
                                            @case("Reçu") class="badge badge-pill badge-primary cielBadge" @break
                                            @endswitch >
                                            {{$transfert_retour->statut}}
                                        </span>
                                    </td>
                                    <td style="font-size: 1.5em">
                                        <a style="color: #467a0f" href="/transfert-retour/{{$transfert_retour->id}}">
                                            <i class="ti-pencil"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align: center">Aucune demande de transfert enregistrée!</td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{$transfert_retours->appends($data)-> links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="container my-4">
    <div class="modal fade" id="modalAddTransfert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal form-material" method="POST" action="{{route('transfert.retour.store')}}">
                    @csrf
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Ajouter une demande de transfert des retours</h4>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="from_city" class="col-sm-12">Expéditeur :</label>
                                    <div class="col-sm-12">
                                        <select id="from_city" name="from_city" class="form-control form-control-line" required @can('superviseur') disabled @endcan>
                                            @can('admin')
                                                <option checked value="">Choisissez la ville</option>
                                                @foreach (App\Ville::orderBy('name')->get() as $ville)
                                                <option value="{{$ville->name}}" class="rounded-circle">
                                                    {{$ville->name}}
                                                </option>
                                                @endforeach
                                            @endcan
                                            @can('superviseur')
                                                <option value="{{$ville->name}}" checked>{{Auth::user()->ville}}</option>
                                            @endcan

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <label for="to_city" class="col-sm-12">Destinataire :</label>
                                    <div class="col-sm-12">
                                        @can('admin')
                                            <select id="to_city" name="to_city" class="form-control form-control-line" required disabled = "true">
                                                <option checked value="">Choisissez la ville</option>
                                                @foreach (App\Ville::orderBy('name')->get() as $ville)
                                                <option value="{{$ville->name}}" class="rounded-circle">
                                                    {{$ville->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                            @endcan
                                        @can('superviseur')
                                            <select name="to_city" class="form-control form-control-line" required>
                                                @if(Auth::user()->ville == 'Casablanca')
                                                    <option checked value="">Choisissez la ville</option>
                                                    @foreach (App\Ville::orderBy('name')->get() as $ville)
                                                    <option value="{{$ville->name}}" class="rounded-circle">
                                                        {{$ville->name}} ({{$ville->prix}}DH)
                                                    </option>
                                                    @endforeach
                                                @else
                                                <option value="Casablanca" checked>Casablanca</option>
                                                @endif
                                            </select>
                                        @endcan

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <textarea name="orderNumbersToAffect" id="hiddenValues" style="display: none"></textarea>
                            <label for="commandeNumbers" class="col-sm-4">Commandes :</label>
                            <div class="col-sm-8">
                                <input class="form-control form-control-line" type="text"  id="commandeNumbers" placeholder="Scanner le QR code de la commade" autofocus>
                            </div>
                        </div>
                        <div class="row">
                            <div id="valuesContainer"></div>
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary" style="color:white" >Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var textarea = document.getElementById('commandeNumbers');
    var valuesContainer = document.getElementById('valuesContainer');
    var hiddenTextarea = document.getElementById('hiddenValues');
    var addedValues = []; // Tableau pour stocker les valeurs déjà ajoutées

    function processValue(value) {
        // Vérifier si la valeur existe déjà dans le tableau
        if (!addedValues.includes(value)) {
            // Créer un élément de badge HTML avec la valeur
            var badge = document.createElement('span');
            badge.classList.add('badge', 'bg-primary');
            badge.style = 'background-color: #467a0f !important;margin: 5px;color: white;padding: 5px !important;cursor:pointer '
            badge.textContent = value;

            // Ajouter le badge au conteneur de valeurs
            valuesContainer.appendChild(badge);

            // Ajouter la valeur au tableau des valeurs ajoutées
            addedValues.push(value);

            // Ajouter la valeur au textarea caché
            if (hiddenTextarea.value === '') {
                hiddenTextarea.value = value;
            } else {
                hiddenTextarea.value += ',' + value;
            }
        }
    }

    function clearTextarea() {
        // Supprimer la valeur du textarea visible
        textarea.value = '';
    }

    textarea.addEventListener('paste', function(event) {
        // Empêcher le comportement par défaut de collage
        event.preventDefault();

        // Récupérer le texte collé
        var pastedText = (event.clipboardData || window.clipboardData).getData('text');

        // Traiter le texte collé
        processValue(pastedText);

        // Nettoyer le textarea visible
        clearTextarea();
    });

    textarea.addEventListener('keydown', function(event) {
        // Si la touche Entrée est pressée
        if (event.key === 'Enter') {
            event.preventDefault(); // Empêcher le comportement par défaut

            var enteredText = textarea.value.trim(); // Récupérer le texte entré

            // Si du texte est entré
            if (enteredText !== '') {
                // Traiter le texte entré
                processValue(enteredText);

                // Nettoyer le textarea visible
                clearTextarea();
            }
        }
    });

    // Ajouter un écouteur d'événement aux badges pour supprimer le badge et la valeur correspondante
    valuesContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('badge')) {
            var badge = event.target;
            var badgeText = badge.textContent;

            // Supprimer le badge du conteneur de valeurs
            valuesContainer.removeChild(badge);

            // Supprimer la valeur correspondante du tableau des valeurs ajoutées
            addedValues = addedValues.filter(function(value) {
                return value !== badgeText;
            });

            // Mettre à jour le textarea caché
            hiddenTextarea.value = addedValues.join(',');
        }
    });
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Sélection des éléments
        var fromCitySelect = document.getElementById("from_city");
        var toCitySelect = document.getElementById("to_city");

        // Stocker les options initiales de la ville de destination
        var initialOptions = toCitySelect.innerHTML;

        // Fonction pour vérifier les conditions et effectuer les actions nécessaires
        function checkAndUpdateCities(event) {
            var selectedFromCity = fromCitySelect.value;
            var selectedToCity = toCitySelect.value;

            if (selectedFromCity && selectedFromCity != 'Casablanca') {
                // Si la ville d'expédition est Casablanca, désactiver la sélection de la ville de destination
                toCitySelect.disabled = true;
                console.log("## ~ checkAndUpdateCities line : 440 ~ toCitySelect:", toCitySelect)

                // Supprimer les options actuelles
                toCitySelect.innerHTML = '';

                // Ajouter une option pour Casablanca sélectionnée
                var option = document.createElement('option');
                option.selected = true;
                option.value = "Casablanca";
                option.text = "Casablanca";
                toCitySelect.add(option);
            }
            // Vérifier les conditions
            else if (selectedFromCity && selectedToCity && ((selectedFromCity !== "Casablanca" && selectedToCity !== "Casablanca") || (selectedToCity != "Casablanca" && selectedFromCity === selectedToCity))) {
                // Afficher une alerte d'erreur
                alert("Erreur : Les villes sélectionnées ne sont pas valides.");
            } else{
                // Si la ville d'expédition n'est pas Casablanca, activer la sélection de la ville de destination
                toCitySelect.disabled = !selectedFromCity;
                console.log("## ~ checkAndUpdateCities line : 455 ~ selectedFromCity:", !selectedFromCity +' ' +selectedFromCity)
                console.log("## ~ event.target.id  line : 455 ~ selectedFromCity:", event?.target.id)

                if(event?.target.id == 'from_city')
                // Réinitialiser les options de la ville de destination aux options initiales
                toCitySelect.innerHTML = initialOptions;
            }
        }

        // Écouteur d'événement pour le changement de la ville d'expéditeur
        fromCitySelect.addEventListener("change", function(event) {
            checkAndUpdateCities(event);
        });

        // Écouteur d'événement pour le changement de la ville de destination
        toCitySelect.addEventListener("change", function(event) {
            checkAndUpdateCities(event);
        });

        // Exécuter la vérification initiale
        checkAndUpdateCities();
    });
</script>
@endsection

