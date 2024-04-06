@extends('racine')

@section('title')
N: {{$commande->numero}}
@endsection


@section('style')
    <style>
        .emp-profile{
            padding: 3%;
            margin-top: 3%;
            margin-bottom: 3%;
            border-radius: 0.5rem;
            background: #fff;

        }
        .profile-img{
            text-align: center;
        }
        .profile-img img{
            width: 70%;
            height: 100%;
        }
        .profile-img .file {
            position: relative;
            overflow: hidden;
            margin-top: -20%;
            width: 70%;
            border: none;
            border-radius: 0;
            font-size: 1em;
            background: #212529b8;
        }
        .profile-img .file input {
            position: absolute;
            opacity: 0;
            right: 0;
            top: 0;
        }
        .profile-head h5{
            color: #333;
        }
        .profile-head h6{
            color: #467a0f;
        }
        .profile-edit-btn{
            border: none;
            border-radius: 1.5rem;
            width: 70%;
            padding: 2%;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
        }
        .proile-rating{
            font-size: 0.75em;
            color: #818182;
            margin-top: 5%;
        }
        .proile-rating span{
            color: #495057;
            font-size: 0.75em;
            font-weight: 600;
        }
        .profile-head .nav-tabs{
            margin-bottom:5%;
        }
        .profile-head .nav-tabs .nav-link{
            font-weight:600;
            border: none;
        }
        .profile-head .nav-tabs .nav-link.active{
            border: none;
            border-bottom:2px solid #467a0f;
        }
        .profile-work{
            padding: 14%;
            margin-top: -15%;
        }
        .profile-work p{
            font-size: 0.75em;
            color: #818182;
            font-weight: 600;
            margin-top: 10%;
        }
        .profile-work a{
            text-decoration: none;
            color: #495057;
            font-weight: 600;
            font-size: 0.75em;
        }
        .profile-work ul{
            list-style: none;
        }
        .profile-tab label{
            font-weight: 600;
        }
        .profile-tab p{
            font-size: 1em;
            font-weight: 600;
            color: #467a0f;
        }
        a {
            color: #467a0f;
        }
        a:hover {
            color: #467a0f;
        }

        #home .row{
            border-bottom-color: #cacaca;
            border-bottom-style: solid;
            border-bottom-width: 2px;
            padding-top: 10px;
        }
    </style>
@endsection






@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-6">
            <h4 class="page-title">Gestion des Colis</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="/commandes">Colis</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$commande->numero}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-6">
            <div class="row float-right">
                @can('fournisseur')
                <a  class="btn btn-warning text-white m-r-5" data-toggle="modal" data-target="#modalReclamation"><i class="fab fa-buffer"></i> <span class="quick-action">Ouvrir un ticket </span></a>

                @if($commande->statut === "Pas de Réponse" || $commande->statut === "Annulée" ||  $commande->statut === "Injoignable" || in_array($commande->statut, array('Annulée sur place','Annulée par téléphone','Colis perdu','Colis endommagé','Livré remboursé','Numéro de téléphone erroné')) )
                <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalRelance"><i class="fas fa-random"></i> <span class="quick-action">Relancer </span></a>
                @endif
                <div class="modal fade" id="modalRelance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de vouloir relancer cette commande ?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>
                                                    Commande numéro: {{$commande->numero}}
                                                </h5>
                                                <p class="proile-rating">Statut : {{$commande->statut}}</p>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    Cliquez sur <b>Ok</b> pour confirmer ou <b>fermer</b> pour annuler la relance

                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                            <form method="GET" action="{{ route('commandes.relance',['commandeId'=> $commande->id]) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary text-white m-r-5">Ok</button>
                                            </form>
                                            </div>
                                        </div>
                                        </div>
                </div>

                <div class="modal fade" id="modalReclamation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <form  method="POST" action="{{route('reclamation.store')}}"  enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="commande" value="{{ $commande->id }}"/>

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <h3 style="text-align:center; position: relative;top: -30px;" class="modal-title" id="exampleModalLabel">OUVRIR UN TICKET</h3>

                        <div class="modal-body" style="padding-bottom: 0;padding-top:0; text-align:center">
                            <h5>
                                <b>Commande numéro : </b> {{$commande->numero}}
                            </h5>
                            <h5>
                                <b>Statut Actuel de la commande : </b> {{$commande->statut}}

                            </h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="objet" class="col-sm-12">Objet du ticket</label>
                                <div class="col-sm-12">
                                    <select onchange="updatedForm(event)" name="objet" id="objet" class="form-control form-control-line" value="{{ old('objet') }}" required>
                                        <option value="Livraison"  selected >Livraison</option>
                                        <option value="Retour"  >Retour</option>
                                        <option value="Retour de fond"  >Retour de fond</option>
                                        <option value="Modification de colis">Modification de colis</option>
                                        <option value="Réclamation"  >Réclamation</option>
                                        <option value="Autres"  >Autres</option>
                                    </select>

                                </div>
                            </div>

                                <div class="update-form" id="updatedForm" style="border-style: solid;margin: 14px; padding: 31px; display:none;">
                                    <div class="form-group">
                                        <label class="col-md-12">Nom et Prénom du destinataire :</label>
                                        <div class="col-md-12">
                                            <input  value="{{ $commande->nom }}" name="nom" type="text" placeholder="Nom & Prénom" class="form-control form-control-line">
                                        </div>
                                    </div>

                                          <fieldset class="form-group">
                                              <legend class="col-form-label  pt-0">Mode de paiement :</legend>
                                              <div class="col-sm-12" style="display: flex;">
                                                <div class="form-check">
                                                  <input  onclick="myFunctionEdit3(this.value)" class="form-check-input" type="radio" name="mode" id="cd" value="cd" @if ($commande->montant != 0) checked   @endif>
                                                  <label class="form-check-label" for="cd">
                                                    à la livraison
                                                  </label>
                                                </div>
                                                <div class="form-check">
                                                  <input  onclick="myFunctionEdit3(this.value)" class="form-check-input" type="radio" name="mode" id="cp" value="cp" @if ($commande->montant == 0) checked   @endif >
                                                  <label class="form-check-label" for="cp">
                                                    carte bancaire
                                                  </label>
                                                </div>

                                              </div>
                                          </fieldset>

                                        <div class="form-group col-md-12" id="montant3" @if ($commande->montant != 0) style="display: block" @else style="display: none" @endif>
                                            <label for="example-email" class="col-md-12">Montant (DH) :</label>
                                            <div class="col-md-12">
                                                <input  value="{{ $commande->montant}}" type="text" class="form-control form-control-line" name="montant" id="example-email">
                                            </div>
                                        </div>


                                    <div class="form-group">
                                        <label class="col-md-12">Téléphone :</label>
                                        <div class="col-md-12">
                                            <input value="{{$commande->telephone}}"  name="telephone" type="text" placeholder="+212 5393-07566" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Adresse :</label>
                                        <div class="col-md-12">
                                            <textarea  name="adresse" rows="5" class="form-control form-control-line">{{ $commande->adresse}}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Note / Commentaire :</label>
                                        <div class="col-md-12">
                                            <textarea  name="note" rows="5" class="form-control form-control-line">{{ $commande->note}}</textarea>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-control-alternative custom-checkbox" style="margin-bottom: 10px;">
                                        <input class="custom-control-input" id="customCheckisFragile" type="checkbox" name="isFragile" value="1" @if ($commande->is_fragile)  checked @endif>
                                        <label class="custom-control-label" for="customCheckisFragile">
                                          <span >Le produit de votre commande est-il fragile ?</span>
                                        </label>
                                    </div>
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" id="customCheckRegister" type="checkbox" name="isOpen" value="1" @if ($commande->isOpen)checked @endif>
                                        <label class="custom-control-label" for="customCheckRegister">
                                          <span >Acceptez-vous que le colis puisse être ouvert par le client final ?</span>
                                        </label>
                                      </div>
                                </div>

                            <div class="form-group row">
                                <label for="Reclamation" class="col-sm-12">Commentaire :</label>
                                <div class="col-sm-12">
                                   <textarea name="description" rows="8" id="Reclamation" class="form-control" required>
                                    {{ old('description') }}
                                   </textarea>

                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 20px;">
                                <div class="custom-file" style="display: flex; justify-content: center;">
                                    <input type="file" accept="image/*" name="image" id="file"  onchange="loadFile(event)" style="display: none">
                                    <label class="alert alert-dismissible alert-success" style="margin: 0;padding: 12px 20px;" for="file" style="cursor: pointer;">Upload Image</label>
                                </div>
                            </div>
                            <div class="form-group" style="display: flex; justify-content: center;">
                                <img id="output" width="200" />
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary text-white m-r-5">Soumettre</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endcan


                    @can('livreur')
                    @if (( $commande->statut === "Pas de Réponse" || $commande->statut === "Livré" || $commande->statut === "Injoignable" || $commande->statut === "En cours" || $commande->statut === "Annulée sur place"
                            || $commande->statut === "Modifiée" || $commande->statut === "Annulée" || $commande->statut === "Relancée" || $commande->statut === "Confirmé sous RDV"
                            || in_array($commande->statut, array('Annulée sur place','Annulée par téléphone','Colis perdu','Colis endommagé','Livré remboursé','Numéro de téléphone erroné')) ))
                    <a  class="btn btn-warning text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormStatut"><i class="fas fa-edit"></i><span class="quick-action">Statut </span></a>
                    @endif
                    @endcan
                    @can('manage-users')
                        @if ( $commande->statut !== "Retour en stock")
                        <a  class="btn btn-warning text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormStatut"><i class="fas fa-edit"></i><span class="quick-action">Statut</span></a>
                        @endif
                    @endcan

                    @can('admin-superviseur-personnel')
                    <a  class="btn btn-dark text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormLivreur"><i class="fas fa-user"></i> <span class="quick-action"> Affecter</span></a>

                    <div class="modal fade" id="modalSubscriptionFormLivreur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <form  method="POST" action="{{route('commande.livreur',['id' => $commande->id])}}">
                                @csrf
                                @method('PATCH')
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Choisissez le livreur au quel vous voulez affecter cette commande ?</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                                <h5>
                                    Commande numéro: {{$commande->numero}}
                                </h5>
                                <h5>
                                    Nom de livreur: {{$livreur->name}}
                                </h5>
                                <div class="form-group row">
                                    <label for="livreur" class="col-sm-4">Livreur :</label>
                                    <div class="col-sm-8">
                                        <select name="livreur" id="livreur" class="form-control form-control-line" value="{{ old('livreur') }}">
                                            <option value=""  selected >Choisissez le livreur</option>
                                            <option selected value="{{$livreur->id}}" class="rounded-circle">
                                                {{$livreur->name}} => ({{count(App\Commande::where('livreur',$livreur->id)->get())}} Commandes)
                                            </option>
                                            @foreach ($livreurs as $livreur)
                                                <option value="{{$livreur->id}}" class="rounded-circle">
                                                    {{$livreur->name}} => ({{count(App\Commande::where('livreur',$livreur->id)->get())}} Commandes)
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">

                              </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary text-white m-r-5">Affecter</button>
                            </div>
                            </form>
                          </div>
                        </div>
                    </div>
                    @endcan

                    @can('delete-commande')
                    @if ($commande->statut === "Nouvelle commande" || $commande->statut === "En attente de ramassage")
                    <a  class="btn btn-primary text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormEdit"><i class="fas fa-edit"></i><span class="quick-action"> Modifier</span></a>
                        @if ($commande->statut === "Nouvelle commande" )
                        <a class="btn btn-secondary text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormDelete"><i class="fas fa-trash-alt"></i><span class="quick-action"> Supprimer</span></a>

                                    <div class="modal fade" id="modalSubscriptionFormDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de vouloir supprimer cette commande ?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>
                                                    Commande numéro: {{$commande->numero}}
                                                </h5>
                                                <p class="proile-rating">Date : {{date_format($commande->created_at,"Y/m/d")}}<span> {{date_format($commande->created_at,"H:i:s")}}</span></p>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                    Cliquez sur <b>Ok</b> pour confirmer ou <b>fermer</b> pour annuler la suppression

                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                            <form method="POST" action="{{ route('commandes.destroy',['commande'=> $commande->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary text-white m-r-5">Ok</button>
                                            </form>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                        @endif
                    @endif

                    @endcan

            </div>
        </div>

    </div>
</div>
<div class="container-fluid">
    <div class="container emp-profile">

        <div class="row">
            {{-- @include('partiels._sessions') --}}

            <div class="col-md-12">
                <div class="profile-head">
                    @can('manage-users')
                        <a style="border: black;
                        border-style: solid;
                        border-radius: 10px;
                        margin-bottom: 10px;
                        padding: 5px;" title="{{$commande->user()->first()->name}} Tel: {{$commande->user()->first()->telephone}}" class=" text-muted waves-effect @if($commande->user()->first()->statut) vip @endif "
                            @can('edit-users')
                                href="{{route('admin.users.edit',$commande->user()->first()->id)}}"
                            @endcan >
                            <img src="{{$commande->user()->first()->image}}" alt="user" class="rounded-circle" width="31" style="border-color: white; border-style: solid; box-shadow: none;">
                            <h5 style="display: inline"> Fournisseur: {{$commande->user()->first()->name}}</h5>
                        </a>
                    @endcan
                    <div style="display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 15px;">
                        <div>
                                @if ($commande->facturer == 0)
                            <span class="badge badge-pill  badge-warning" style="color: white">
                                    Commande Non facturée
                            </span>
                                @else
                            <a href="{{route('facture.infos',$commande->facturer)}}" class="badge badge-pill  badge-warning" style="color: white">
                                Commande Faturée
                            </a>
                                @endif
                        </div>
                        <div>
                            @if ($commande->isChanged == 1)
                            <span class="badge badge-pill  badge-info mt-2" style="color: white">
                                    Commande de Change
                            </span>
                            @endif

                        </div>
                        <div>
                            <h5 >
                            <span class="badge badge-pill  badge-danger mt-2" style="color: white">
                                Nombre de relance : {{$Rtotal}}
                            </span>
                            </h5>
                        </div>
                    </div>
                            <h5>
                                Commande numéro: <span style="color: #467a0f">{{$commande->numero}}</span>
                                @switch($commande->statut)
                                    @case("En attente de ramassage")
                                        <a class="badge" style="color: white; background-color: orange;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Ramassé par le livreur")
                                        <a class="badge" style="color: white; background-color: blue;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Reçue dans le hub régional")
                                        <a class="badge" style="color: white; background-color: purple;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Prêt à transférer")
                                        <a class="badge" style="color: white; background-color: #2472a3;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Envoyée vers le hub central")
                                        <a class="badge" style="color: white; background-color: green;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Envoyée vers le hub régional")
                                        <a class="badge" style="color: white; background-color: green;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Reçue dans le hub central")
                                        <a class="badge" style="color: white; background-color: teal;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Prêt à livrer")
                                        <a class="badge" style="color: white; background-color: rgb(78, 67, 166);">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Affectée au livreur")
                                        <a class="badge" style="color: white; background-color: brown;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Nouvelle commande")
                                        <a class="badge" style="color: white; background-color: #ceab1c;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("En cours")
                                        <a class="badge" style="color: white; background-color: skyblue;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Relancée")
                                        <a class="badge" style="color: white; background-color: darkorange;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Livré")
                                        <a class="badge" style="color: white; background-color: green;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Injoignable")
                                        <a class="badge" style="color: white; background-color: red;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Pas de Réponse")
                                        <a class="badge" style="color: white; background-color: grey;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Annulée sur place")
                                        <a class="badge" style="color: white; background-color: darkgrey;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Annulée par téléphone")
                                        <a class="badge" style="color: white; background-color: lightgrey;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Colis perdu")
                                        <a class="badge" style="color: white; background-color: black;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Colis endommagé")
                                        <a class="badge" style="color: white; background-color: darkred;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Livré remboursé")
                                        <a class="badge" style="color: white; background-color: gold;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @case("Numéro de téléphone erroné")
                                        <a class="badge" style="color: white; background-color: #944444;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                        @break
                                    @default
                                        <a class="badge" style="color: white; background-color: #944444;">
                                            <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                        </a>
                                @endswitch
                            </h5>
                            @can('manage-users')
                                @if ($client == true)
                                <div class="modal fade" id="validRetour" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Validation en stock</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                        Cliquez sur <b>valider</b> pour rajouter les produits de cette commande en stock
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                        <a  href="{{route('commande.valideRetour',$commande->id)}}" class="btn btn-primary">Valider le retour</a>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                @endif

                            @endcan
                            <div class="col-md-12 row">
                                <div>
                                    <button type="button" class="btn btn-info text-white m-r-5" data-toggle="modal" data-target="#ticketPrint"><i class="fas fa-print"></i> Imprimer</button>
                                </div>
                                @can('manage-users')
                                <div>
                                    <button type="button" style="background-color: #ffab01;" class="btn text-white m-r-5" data-toggle="modal" data-target="#horszone"><i class="fas fa-route"></i> Hors Zone</button>
                                </div>
                                @if ($client == true)
                                    @if ( $commande->statut === "Pas de Réponse" || $commande->statut === "Injoignable" || $commande->statut === "Annulée sur place"
                                            || $commande->statut === "Retour" || $commande->statut === "Annulée"
                                            || in_array($commande->statut, array('Annulée sur place','Annulée par téléphone','Colis perdu','Colis endommagé','Livré remboursé','Numéro de téléphone erroné'))  )
                                    <div>
                                        <button type="button"  class="btn btn-danger text-white m-r-5" title="Valider dans le stock"  data-toggle="modal" data-target="#validRetour"><i class="fas fa-clipboard-check"></i> Valider le retour</button>
                                    </div>
                                    @endif

                                @endif
                                @endcan
                                 @can('client-admin')
                                <div>
                                    <a href="{{ route('commande.change',['commande'=> $commande]) }}" class="btn text-white m-r-5" type="button" style="background-color: #dcdc3a;"><i class="fas fa-undo"></i> Colis de Change</a>
                                </div>
                                 @endcan
                            </div>
                            <p class="proile-rating">Date d'ajout : {{date_format($commande->created_at,"Y/m/d")}}<span> {{date_format($commande->created_at,"H:i:s")}}</span></p>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Informations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Historique des statuts</a>
                        </li>

                        @can('gestion-stock')
                        <li class="nav-item">
                            <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="false">Details</a>
                        </li>
                        @endcan

                        @can('ramassage-commande')
                        <li class="nav-item">
                            <a class="nav-link" id="relances-tab" data-toggle="tab" href="#relances" role="tab" aria-controls="relances" aria-selected="false">Relances</a>
                        </li>
                        @endcan
                        @cannot('livreur')
                        <li class="nav-item">
                            <a class="nav-link" id="Tickets-tab" data-toggle="tab" href="#Tickets" role="tab" aria-controls="Tickets" aria-selected="false">Tickets/Réclamation</a>
                        </li>
                        @endcannot
                    </ul>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ticketPrint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Choisissez le type de format</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-footer">
                    <a target="_blank" class="btn btn-info text-white m-r-5" href="{{ route('pdf.gen',['id'=> $commande->id]) }}">Format A6</a>
                    <a target="_blank" class="btn btn-primary text-white m-r-5" href="{{ route('pdf.genA8',['id'=> $commande->id]) }}">Format A8</a>
                </div>
              </div>
            </div>
          </div>
          @can('manage-users')
        <div class="modal fade" id="horszone" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <form action="{{route('commande.outRange',['commande' => $commande])}}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Veuillez mentionnez le prix de livraison sur cette zone</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body mx-3">
                        <div class="form-group col-md-12" >
                            <label for="horsZonePrice" class="col-md-12">Prix de livraison (DH) :</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control form-control-line" name="horsZone" id="horsZonePrice">
                            </div>
                        </div>
                      </div>
                      <div class="modal-body mx-3">
                        <div class="form-group col-md-12" >
                            <label for="horsZoneLivreurPart" class="col-md-12">Part de livreur (DH) :</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control form-control-line" name="horsZoneLivreurPart" id="horsZoneLivreurPart">
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                          <button type="submit"class="btn btn-primary text-white m-r-5" >Valider</button>
                      </div>
                  </form>

              </div>
            </div>
          </div>
          @endcan


        <div class="row">

            <div class="col-md-12">
                <div class="tab-content profile-tab" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nom du destinataire : </label>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="text-transform: uppercase">{{$commande->nom}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Téléphone :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->telephone}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Adresse :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->adresse}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Ville :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->ville}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Secteur :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->secteur}}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Montant :</label>
                                    </div>
                                    <div class="col-md-6">
                                        @if ($commande->montant > 0)
                                        <p>{{$commande->montant}} DH</p>
                                        @else
                                        <p> <i class="far fa-credit-card"></i> CARD PAYMENT
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @can('livreur-admin')
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>La part du livreur :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->livreurPart}} MAD</p>
                                    </div>
                                </div>
                                @endcan

                                @cannot('livreur')
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Prix de livraison :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->prix}} DH</p>
                                    </div>
                                </div>
                                @endcannot

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nombre de colis :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->colis}}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Statut de la commande :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->statut}} ({{$commande->updated_at->diffForHumans()}})</p>
                                        <p class="proile-rating">Date : {{date_format($commande->updated_at,"Y/m/d")}}<span> {{date_format($commande->updated_at,"H:i:s")}}</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>La date d'ajout :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{date_format($commande->created_at,"Y/m/d H:i:s")}}</p>
                                        <p class="proile-rating">il y'a: {{$commande->created_at->diffForHumans()}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Note / Commentaire:</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{$commande->note}}</p>
                                    </div>
                                </div>
                                @if ($commande->ramassage_id)
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Demande de ramassage :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            <a class="btn btn-light" href="{{route('ramassage.show',$commande->ramassage_id)}}">
                                            {{$commande->ramassage()->first()->reference}}
                                          </a></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Bon de livraison:</label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            <a class="btn btn-light" href="{{ route('bon.infos',['id'=> $commande->traiter ]) }}">
                                                <i class="fas fa-print"></i>
                                          </a>
                                        </p>
                                    </div>
                                </div>
                                @endif
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-2">
                                <p>Modifié par</p>
                            </div>
                            <div class="col-md-3">
                                <label>STATUT</label>
                            </div>
                            <div class="col-md-3">
                                <p>DATE</p>
                            </div>
                            <div class="col-md-3">
                                <p>Commentaire</p>
                            </div>
                            <div class="col-md-1">
                                <p>Pièce joint</p>
                            </div>
                        </div>
                        @foreach ($statuts as $index => $statut)
                        <div class="row">
                            <div class="col-md-2">
                                <p>
                                    <a title="{{$par[$index+1]->name}} Tel: {{$par[$index+1]->telephone}}" class="waves-effect" style="color: black"
                                        @can('edit-users')
                                            href="{{route('admin.users.edit',$par[$index+1]->id)}}"
                                        @endcan >
                                        <img src="{{$par[$index+1]->image}}" alt="user" class="rounded-circle" width="31" style="width: 10%"
                                        >
                                        {{$par[$index+1]->name}}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-3">
                            <label>
                                @switch($statut->name)
                                    @case("En attente de ramassage")
                                        <a class="badge" style="color: white; background-color: orange;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Ramassé par le livreur")
                                        <a class="badge" style="color: white; background-color: blue;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Reçue dans le hub régional")
                                        <a class="badge" style="color: white; background-color: purple;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Prêt à transférer")
                                        <a class="badge" style="color: white; background-color: #2472a3;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Envoyée vers le hub central")
                                        <a class="badge" style="color: white; background-color: green;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Envoyée vers le hub régional")
                                        <a class="badge" style="color: white; background-color: green;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Reçue dans le hub central")
                                        <a class="badge" style="color: white; background-color: teal;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Prêt à livrer")
                                        <a class="badge" style="color: white; background-color: rgb(78, 67, 166);">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Affectée au livreur")
                                        <a class="badge" style="color: white; background-color: brown;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Nouvelle commande")
                                        <a class="badge" style="color: white; background-color: #ceab1c;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("En cours")
                                        <a class="badge" style="color: white; background-color: skyblue;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Relancée")
                                        <a class="badge" style="color: white; background-color: darkorange;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Livré")
                                        <a class="badge" style="color: white; background-color: green;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Injoignable")
                                        <a class="badge" style="color: white; background-color: red;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Pas de Réponse")
                                        <a class="badge" style="color: white; background-color: grey;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Annulée sur place")
                                        <a class="badge" style="color: white; background-color: darkgrey;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Annulée par téléphone")
                                        <a class="badge" style="color: white; background-color: lightgrey;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Colis perdu")
                                        <a class="badge" style="color: white; background-color: black;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Colis endommagé")
                                        <a class="badge" style="color: white; background-color: darkred;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Livré remboursé")
                                        <a class="badge" style="color: white; background-color: gold;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @case("Numéro de téléphone erroné")
                                        <a class="badge" style="color: white; background-color: #944444;">
                                            <span style="font-size: 1.25em">{{$statut->name}}</span>
                                        </a>
                                        @break
                                    @default
                                    <a class="badge" style="color: white; background-color: #944444;">
                                        <span style="font-size: 1.25em">{{$statut->name}}</span>
                                    </a>
                                @endswitch
                            </label>
                            @if ($statut->name == "Confirmé sous RDV" && $statut->postponed_at != null)
                                <span class="badge">Pour le : {{\Carbon\Carbon::parse($statut->postponed_at)->format('j , m, Y')}}</span>
                            @endif

                            </div>
                            <div class="col-md-3">
                                <p style="text-transform: uppercase;">{{ \Carbon\Carbon::parse($statut->created_at)->locale('fr_FR')->isoFormat('dddd DD MMMM YYYY') }} |
                                    {{ \Carbon\Carbon::parse($statut->created_at)->formatLocalized('%H:%M') }}</p>
                            </div>
                            <div class="col-md-3">
                                <p>{{$statut->comment}}</p>
                            </div>
                            <div class="col-md-1">
                                <p>
                                    @if ($statut->joint)
                                    <img style="cursor: pointer" id="{{$statut->joint}}" src="/uploads/statuts/{{$statut->joint}}"width="50" onclick="showImage(event)" />
                                    @else
                                    -
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endforeach
                        @can('fournisseur')
                            @if ($statut->name == "En cours")
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Livreur :  {{$livreur->name}}</label><br/>
                                    <p>
                                        {{$livreur->telephone}}
                                    </p>
                                </div>
                            </div>
                            @endif
                        @endcan
                    </div>
                    @can('gestion-stock')
                    <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                        @foreach ($produits as $index => $produit)
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="/produit/{{$produit->id}}" title="{{$produit->libelle}}" class=" text-muted waves-effect waves-dark pro-pic">
                                        <img src="/uploads/produit/{{$produit->photo}}" alt="user" class="rounded-circle" width="31">
                                    </a>
                                    <label>{{$produit->libelle}}</label>
                                </div>
                                <div class="col-md-4">
                                    <p style="text-transform: uppercase">Ref: {{$produit->reference}}</p>
                                </div>
                                <div class="col-md-4">
                                    <p style="text-transform: uppercase">QTE: {{$liaisons[$index]->qte}}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endcan

                    @can('ramassage-commande')
                    <div class="tab-pane fade" id="relances" role="tabpanel" aria-labelledby="relances-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Date de relance</label>
                            </div>
                            <div class="col-md-6">
                                <p>Commentaire</p>
                            </div>
                            <div class="col-md-3">
                                <p>Relancée par</p>
                            </div>
                        </div>
                        @forelse ($relances as $index => $relance)
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{$relance->created_at}}</label>
                                </div>
                                <div class="col-md-6">
                                    <p style="text-transform: uppercase">{{$relance->comment}}</p>
                                </div>
                                <div class="col-md-3">
                                    <p style="text-transform: uppercase">{{$Rpar[$index]->name}}</p>
                                </div>
                            </div>
                            @empty
                            <div class="row">
                                <div class="col-md-12">
                                    Aucune Relance
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @endcan
                    <div class="tab-pane fade" id="Tickets" role="tabpanel" aria-labelledby="Tickets-tab">
                        @include('reclamation._comments', ['from' => 'show'])
                    </div>
                </div>
            </div>
        </div>

</div>
</div>

@can('delete-commande')
@if ($commande->statut === "Nouvelle commande" || $commande->statut === "En attente de ramassage" )
<div class="container my-4">
    <div class="modal fade" id="modalSubscriptionFormEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Modifier la Commande</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="POST" action="{{route('commandes.update',['commande' => $commande])}}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="col-md-12">Nom et Prénom du destinataire :</label>
                                    <div class="col-md-12">
                                        <input  value="{{ old('nom',$commande->nom) }}" name="nom" type="text" placeholder="Nom & Prénom" class="form-control form-control-line">
                                    </div>
                                </div>

                                      <fieldset class="form-group">
                                          <legend class="col-form-label  pt-0">Mode de paiement :</legend>
                                          <div class="col-sm-12" style="display: flex;">
                                            <div class="form-check">
                                              <input  onclick="myFunctionEdit2(this.value)" class="form-check-input" type="radio" name="mode" id="myFunctionEdit2cd" value="cd"
                                                @if ($commande->montant != 0) checked @endif >
                                              <label class="form-check-label" for="myFunctionEdit2cd">
                                                à la livraison
                                              </label>
                                            </div>
                                            <div class="form-check">
                                              <input  onclick="myFunctionEdit2(this.value)" class="form-check-input" type="radio" name="mode" id="myFunctionEdit2cp" value="cp"
                                                @if ($commande->montant == 0) checked   @endif >
                                              <label class="form-check-label" for="myFunctionEdit2cp">
                                                carte bancaire
                                              </label>
                                            </div>

                                          </div>
                                      </fieldset>

                                      <div class="form-group col-md-12" id="montant2"
                                      @if ($commande->montant != 0)
                                      style="display: block"
                                      @else
                                      style="display: none"
                                      @endif


                                       >
                                        <label for="example-email" class="col-md-12">Montant (DH) :</label>
                                        <div class="col-md-12">
                                            <input  value="{{ old('montant',$commande->montant) }}" type="text" class="form-control form-control-line" name="montant" id="example-email">
                                        </div>
                                    </div>


                                <div class="form-group">
                                    <label class="col-md-12">Téléphone :</label>
                                    <div class="col-md-12">
                                        <input value="{{ old('telephone',$commande->telephone) }}"  name="telephone" type="text" placeholder="+212 5393-07566" class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Adresse :</label>
                                    <div class="col-md-12">
                                        <textarea  name="adresse" rows="5" class="form-control form-control-line">{{ old('adresse',$commande->adresse) }}</textarea>
                                    </div>
                                </div>
                                @if ($commande->statut !== "Nouvelle commande")
                                @can('manage-users')
                                <div class="form-group">
                                        <label class="col-sm-12">Ville :</label>
                                        <div class="col-sm-12">
                                            <select name="ville" class="form-control form-control-line" value="{{ old('ville',$commande->ville) }}" required>
                                            <option value="{{$commande->ville}}" checked>{{$commande->ville}}</option>
                                            @foreach ($villes as $ville)
                                            <option value="{{$ville->name}}" class="rounded-circle">
                                                {{$ville->name}}
                                            </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endcan
                                @can('fournisseur')
                                <div class="form-group" style="display: none">
                                        <label class="col-sm-12">Ville :</label>
                                        <div class="col-sm-12">
                                            <select name="ville" class="form-control form-control-line" value="{{ old('ville',$commande->ville) }}" required>
                                            <option value="{{$commande->ville}}" checked selected>{{$commande->ville}}</option>
                                            </select>
                                        </div>
                                    </div>
                                @endcan
                                @else
                                <div class="form-group">
                                    <label class="col-sm-12">Ville :</label>
                                    <div class="col-sm-12">
                                        <select name="ville" class="form-control form-control-line" value="{{ old('ville',$commande->ville) }}" required>
                                        <option value="{{$commande->ville}}" checked>{{$commande->ville}}</option>
                                        @foreach ($villes as $ville)
                                        <option value="{{$ville->name}}" class="rounded-circle">
                                            {{$ville->name}}
                                        </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-md-12">Note / Commentaire :</label>
                                    <div class="col-md-12">
                                        <textarea  name="note" rows="5" class="form-control form-control-line">{{ old('note',$commande->note) }}</textarea>
                                    </div>
                                </div>
                                <div class="custom-control custom-control-alternative custom-checkbox" style="margin-bottom: 10px;">
                                    <input class="custom-control-input" id="customCheckisFragile" type="checkbox" name="isFragile" value="1"
                                        @if ($commande->is_fragile)
                                        checked
                                        @endif
                                    >
                                    <label class="custom-control-label" for="customCheckisFragile">
                                      <span >Le produit de votre commande est-il fragile ?</span>
                                    </label>
                                </div>
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" id="customCheckRegister" type="checkbox" name="isOpen" value="1"
                                    @if ($commande->isOpen)
                                         checked
                                    @endif
                                    >
                                    <label class="custom-control-label" for="customCheckRegister">
                                      <span >Acceptez-vous que le colis puisse être ouvert par le client final ?</span>
                                    </label>
                                  </div>
                                <div class="form-group">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button class="btn btn-warning">Modifier</button>

                                    </div>
                                </div>
                            </form>
                            @if ($errors->any())
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>
                                        <strong>{{$error}}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                              </div>
                              @endif
                        </div>

                      </div>
                    </div>
    </div>
</div>
@endif
@endcan

<div class="modal fade" id="myModalImage" tabindex="-1" role="dialog" aria-labelledby="myModalImageTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <img class="modal-content" id="modalImg">
        </div>
      </div>
    </div>
  </div>

<div class="container my-4">
    <div class="modal fade" id="modalSubscriptionFormStatut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Changer le statut</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="POST" action="{{route('statut.admin',['id' => $commande->id])}}"  enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <label for="etat" class="col-sm-12">Statut :</label>
                                    <div class="col-sm-12">
                                        <select id="etat" onchange="reporter()" name="statut" class="form-control form-control-line" value="{{ old('statut',$commande->statut) }}" required>
                                            @can('manage-users')
                                                <option>Nouvelle commande</option>
                                                <option>Ramassée</option>
                                                <option>Prêt à livrer</option>
                                                <option>Affectée au livreur</option>
                                                <option>En cours</option>
                                                <option>Relancée</option>
                                            @endcan
                                                <option>Livré</option>
                                                <option>Injoignable</option>
                                                <option>Pas de Réponse</option>
                                                <option>Annulée sur place</option>
                                                <option>Annulée par téléphone</option>
                                                <option>Colis perdu</option>
                                                <option>Colis endommagé</option>
                                                <option>Livré remboursé</option>
                                                <option>Numéro de téléphone erroné</option>
                                            @cannot('livreur')
                                                <option>Retour</option>
                                            @endcannot
                                                <option>Confirmé sous RDV</option>
                                                <option>Annulée</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="display: none" id="prevu">
                                    <label for="datePrevu" class="col-sm-12">Date Prévue :</label>
                                    <div class="col-sm-12">
                                      <input class="form-control" name="prevu_at" type="date" id="datePrevu">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12">Commentaire :</label>
                                    <div class="col-sm-12">
                                        <textarea  name="commentaire" rows="5" class="form-control form-control-line">{{ old('commentaire') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 20px;">
                                    <label class="col-sm-12">Fichier joint :</label>
                                    <div class="custom-file col-sm-12" style="display: flex; justify-content: center;">
                                        <input type="file" id="fileInput" name="fileInput" accept="image/*, .pdf" onchange="previewFile()" style="display: none">
                                        <label class="alert alert-dismissible alert-success" style="margin: 0;padding: 12px 20px;" for="fileInput" style="cursor: pointer;">Upload Image</label>
                                    </div>
                                </div>
                                <div id="preview"></div>

                                <div class="form-group">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button class="btn btn-warning">Enregistrer</button>

                                    </div>
                                </div>
                            </form>
                            @if ($errors->any())
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>
                                        <strong>{{$error}}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                              </div>
                              @endif
                        </div>

                      </div>
                    </div>
    </div>
</div>

@endsection

@section('javascript')

<script>

    function showImage(event){
        $('#myModalImage').modal('show');
        modalImg.src = event.target.src;
    }

    function previewFile() {
      var preview = document.getElementById('preview');
      var file = document.querySelector('input[type=file]').files[0];
      var reader = new FileReader();

      reader.onloadend = function () {
        var fileType = file.type.split('/')[0];
        if (fileType === 'image') {
          var img = document.createElement('img');
          img.src = reader.result;
          img.style.width = '50%';
          preview.innerHTML = '';
          preview.appendChild(img);
        } else if (fileType === 'application' && file.type === 'application/pdf') {
          preview.innerHTML = 'Aperçu non disponible pour les fichiers PDF.';
        } else {
          preview.innerHTML = 'Aperçu non disponible pour ce type de fichier.';
        }
      }

      if (file) {
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '';
      }
    }
  </script>

<script>
    var xx = document.getElementById("prevu");
    function reporter() {

    var test = document.getElementById("etat").value;
    //alert(test);
    if(test=='Confirmé sous RDV'){
        xx.style.display = "block";
    }
    else{
        xx.style.display = "none";
    }
    }

    function updatedForm(event){
        let objectValue = event.target.value;
        updatedFormId = document.getElementById("updatedForm").style.display =  (objectValue == 'Modification de colis')  ? 'block' : 'none'
    }
</script>

<script>
    function myFunctionEdit2(mode) {
        var y = document.getElementById("montant2");
        if(mode == 'cd' && y.value != 0 ){
            y.style.display = "block";
        }
        else{
            y.style.display = "none";
        }
    }

    function myFunctionEdit3(mode) {
        var y = document.getElementById("montant3");
        if(mode == 'cd' && y.value != 0 ){
            y.style.display = "block";
        }
        else{
            y.style.display = "none";
        }
    }
</script>
<script>
    function myFunction() {
        var xx = document.getElementById("secteur");
    var test = document.getElementById("ville").value;
    if(test=='Tanger'){
        xx.style.display = "block";
    }
    else{
        xx.style.display = "none";
    }
    }
</script>

<script>
    function myFunction2(mode) {
        var yy = document.getElementById("montant");

        if(mode == 'cd'){
            yy.style.display = "block";
            console.log("cd");
        }
        else{
            yy.style.display = "none";
            console.log("cp");
        }
    }
    var loadFile = function(event) {
	var image = document.getElementById('output');
	image.src = URL.createObjectURL(event.target.files[0]);
};
function loadFile2(event, reclamationId) {
	var image2 = document.getElementById('output'+reclamationId);
	image2.src = URL.createObjectURL(event.target.files[0]);
};

document.addEventListener("DOMContentLoaded", function () {
    function filterTable() {
        var valueInput = myInput.value.toLowerCase();
        var valueInputStatut = myInputStatut.value.toLowerCase();
        var tableRows = document.querySelectorAll("#myTable tr");

        tableRows.forEach(function (row) {
            var textContent = row.textContent.toLowerCase();
            var showRow = (valueInput === 'all' || textContent.indexOf(valueInput) > -1) &&
                          (valueInputStatut === 'all' || textContent.indexOf(valueInputStatut) > -1);

            row.style.display = showRow ? 'table-row' : 'none';
        });
    }

    var myInput = document.getElementById("myInput");
    myInput.addEventListener("change", filterTable);

    var myInputStatut = document.getElementById("myInputStatut");
    myInputStatut.addEventListener("change", filterTable);

    // Initial filtering when the page loads
    filterTable();
});


</script>
@endsection
