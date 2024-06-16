
@extends('racine')

@section('title')
Gestion des retours
@endsection


@section('style')
    <style>
        .list-command{
            cursor: pointer;
            border-width: 3px;
        }
        .list-command:hover{
            background-color: #f2f2f2;
        }
        .orangeBadge{
            background-color: #AF642D;
        }
        .violetBadge{
            background-color: #ab03ca;
        }
        .cielBadge{
                background-color: #00BCD4;
        }
        .relanceBadge{
        background-color: #867f43;
        }
        .dropdown.dropdown-lg .dropdown-menu {
            margin-top: -1px;
            padding: 6px 20px;
        }
        .input-group-btn .btn-group {
            display: flex !important;
        }
        .btn-group .btn {
            border-radius: 0;
            margin-left: -1px;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .btn-group .form-horizontal .btn[type="submit"] {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        }
        .form-horizontal .form-group {
            margin-left: 0;
            margin-right: 0;
        }
        .form-group .form-control:last-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        @media screen and (min-width: 768px) {
            #adv-search {
                width: 500px;
                margin: 0 auto;
            }
            .dropdown.dropdown-lg {
                position: static !important;
            }
            .dropdown.dropdown-lg .dropdown-menu {
                min-width: 500px;
            }
        }
        .page-link {
            color: #467a0f !important;
        }
        .page-item.active .page-link {

            background-color: #467a0f !important;
            border-color: #467a0f !important;
            color: #fff !important;
        }
    </style>
@endsection


@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Gestion des retours</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="/commandes">Colis</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
        <div class="row float-right d-flex ">
            @cannot('livreur')
                <div class="m-r-5" style="margin-right: 10px;">
                    <a  class="btn btn-warning text-white"  data-toggle="modal" data-target="#modalSearchForm"><i class="fa fa-search"></i></a>
                </div>
                <div class="m-r-5" style="margin-right: 10px;">
                    @include('retour._orderAffectation')
                </div>
            @endcannot
        </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        @if (session()->has('search'))
        <div class="alert alert-dismissible alert-warning col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Aucun résultat trouvé !</strong> Il n'existe aucun numero de commande et aucun statut avec : {{session()->get('search')}}  </a>.
          </div>
        @endif
        @if (session()->has('statut'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succès !</strong> La commande a été bien enregistrée <a  href="commandes/{{session()->get('statut')}}" class="alert-link">(Voir la commande)</a>.
          </div>
        @endif

        @if (session()->has('delete'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succés !</strong> La commande numero {{session()->get('delete')}} à été bien supprimée !
          </div>
        @endif

        @if (session()->has('stock_insuf'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Attention !</strong> Le stock de l'article {{session()->get('stock_insuf')}} est insuffisant !
          </div>
        @endif

        @if (session()->has('produit_required'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Attention !</strong> Il faut mentionner les produits de la commande
          </div>
        @endif

        @if (session()->has('edit'))
        <div class="alert alert-dismissible alert-info col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succés !</strong> Le statut de la commande numero {{session()->get('edit')}} à été bien edité !
          </div>
        @endif
        @if (session()->has('noedit'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Attention !</strong>vous ne pouvez pas changer le statut La commande numero {{session()->get('noedit')}}
          </div>
        @endif

        @if (session()->has('nonExpidie'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Attention !</strong>Commande déjà traitée  {{session()->get('nonExpidie')}} <br>
                vous pouvez modifier que les statuts des commandes qui ont le statut <b>Nouvelle commande</b>
        </div>
        @endif
        @if (session()->has('blgenere'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Attention !</strong>vous ne pouvez pas changer le statut de La commande numero {{session()->get('blgenere')}} <br>
                => le bon de livraison pour cette commande à été déjà généré
        </div>
        @endif
        @if (session()->has('blNongenere'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Attention !</strong>vous ne pouvez pas changer le statut de La commande numero {{session()->get('blNongenere')}} sans générer le bon de livraison <br>

        </div>
        @endif
    </div>
    <div class="card">
        <div class="collapse show" id="mycard-action">
            <div class="card-body">
                <div style="display: flex;align-items: center;align-content: stretch;flex-wrap: wrap;justify-content: space-evenly;">
                    <a href="/retour/filter?statut=Toutes Les Commandes" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;background-color:rgb(124, 95, 204) !important;" class="btn col-3"> Toutes Les Commandes</a>
                    <a href="/retour/filter?statut=Nouveau" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;background-color:rgb(226, 223, 54) !important;" class="btn col-3"> Nouveau</a>
                    <a href="/retour/filter?statut=Prête à retourner" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;" class="btn col-3 btn-info text-white"> Prête à retourner</a>
                    <a href="/retour/filter?statut=Arrivée au Hub Central" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;" class="btn col-3 btn-warning text-white"> Arrivée au Hub Central</a>
                    <a href="/retour/filter?statut=En Route vers le Hub Régional" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;" class="btn col-3 btn-primary">En Route vers le Hub Régional</a>
                    <a href="/retour/filter?statut=Arrivée au Hub Régional" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;" class="btn col-3 badge-dark text-white">Arrivée au Hub Régional</a>
                    <a href="/retour/filter?statut=En Route vers le Hub Central" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;background-color:rgb(42, 104, 165) !important;" class="btn col-3 text-white">En Route vers le Hub Central</a>
                    <a href="/retour/filter?statut=En Route vers le Propriétaire" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;" class="btn col-3 btn-danger text-white">En Route vers le Propriétaire</a>
                    <a href="/retour/filter?statut=Retour Livré" style="display:block ; margin: 0.5rem; font-size: 0.8em;color: white; cursor:pointer;margin-top:0.5rem;background-color:rgb(42, 165, 157) !important;" class="btn col-3 text-white">Retour Livré</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gestion de retour des commandes</h4>
                    <h6 class="card-subtitle">Nombre total des commandes : <code>{{$total}} Commandes</code> .</h6>
                    <input class="form-control" id="myInput" type="text" placeholder="Rechercher...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size: 0.72em;">
                        <thead>
                            <tr>
                                @can('edit-users')
                                <th scope="col">Client</th>
                                @endcan
                                <th scope="col">Numero Commande</th>
                                @can('ecom-client')
                                <th scope="col">Nom Complet</th>
                                <th scope="col">Téléphone</th>
                                <th scope="col">Montant</th>
                                @endcan
                                <th scope="col">Ville de livraison</th>
                                @cannot('ecom-client')
                                <th scope="col">Ville du vendeur</th>
                                @endcannot
                                @cannot('livreur')
                                <th scope="col">Prix de Livraison</th>
                                @endcannot
                                <th scope="col">Statut de non livré</th>
                                <th scope="col">Statut de retour</th>
                                @can('edit-users')
                                <th>Livreur</th>
                                @endcan
                                <th scope="col">Date de création</th>
                                <th scope="col">Date de dérnière modification</th>
                                <th scope="col">Détail</th>

                            </tr>
                        </thead>
                        <tbody id="myTable">
                           @forelse ($commandes as $index => $commande)
                           <tr>
                            @can('edit-users')
                            <th scope="row">
                                <a title="{{$users[$index]->name}}" class=" text-muted waves-effect waves-dark pro-pic"
                                    @can('edit-users')
                                        href="{{route('admin.users.edit',$users[$index]->id)}}"
                                    @endcan
                                    >
                                    <img src="{{$users[$index]->image}}" alt="user" class="rounded-circle" width="31"> <br> {{$users[$index]->name}}
                                </a>
                            </th>
                            @endcan
                            <th scope="row">{{$commande->numero}}</th>
                            @can('ecom-client')
                            <td>{{$commande->nom}}</td>
                            <td>{{$commande->telephone}}</td>
                            <td>  @if ($commande->montant > 0) {{$commande->montant}} DH @else <i class="far fa-credit-card"></i> CARD PAYMENT  @endif</td>
                            @endcan
                            <td>{{$commande->ville}}</td>
                            @cannot('ecom-client')
                            <td>{{$users[$index]->ville}}</td>
                            @endcannot
                            @cannot('livreur')
                            <td>{{$commande->prix}} DH</td>
                            @endcannot
                            <td>
                                <a  style="color: white" class="badge badge-pill badge-danger">
                                     <span style="font-size: 1.25em">{{$commande->statut}}</span>
                                </a>
                            </td>
                            <td>
                                @switch(App\Retour::where('commande_id', $commande->id)->first()->status)
                                @case('Toutes Les Commandes')
                                    <a style="color: white; background-color: rgb(124, 95, 204) !important;" class="badge badge-pill">
                                        <span style="font-size: 1.25em">Toutes Les Commandes</span>
                                    </a>
                                    @break
                                @case('Nouveau')
                                    <a style="color: white; background-color: rgb(226, 223, 54) !important;" class="badge badge-pill">
                                        <span style="font-size: 1.25em">Nouveau</span>
                                    </a>
                                    @break
                                @case('Prête à retourner')
                                    <a style="color: white;" class="badge badge-pill badge-info">
                                        <span style="font-size: 1.25em">Prête à retourner</span>
                                    </a>
                                    @break
                                @case('Arrivée au Hub Central')
                                    <a style="color: white;" class="badge badge-pill badge-warning">
                                        <span style="font-size: 1.25em">Arrivée au Hub Central</span>
                                    </a>
                                    @break
                                @case('En Route vers le Hub Régional')
                                    <a style="color: white;" class="badge badge-pill badge-primary">
                                        <span style="font-size: 1.25em">En Route vers le Hub Régional</span>
                                    </a>
                                    @break
                                @case('Arrivée au Hub Régional')
                                    <a style="color: white;" class="badge badge-pill badge-dark">
                                        <span style="font-size: 1.25em">Arrivée au Hub Régional</span>
                                    </a>
                                    @break
                                @case('En Route vers le Hub Central')
                                    <a style="color: white; background-color: rgb(42, 104, 165) !important;" class="badge badge-pill">
                                        <span style="font-size: 1.25em">En Route vers le Hub Central</span>
                                    </a>
                                    @break
                                @case('En Route vers le Propriétaire')
                                    <a style="color: white;" class="badge badge-pill badge-danger">
                                        <span style="font-size: 1.25em">En Route vers le Propriétaire</span>
                                    </a>
                                    @break
                                @case('Retour Livré')
                                    <a style="color: white; background-color: rgb(42, 165, 157) !important;" class="badge badge-pill">
                                        <span style="font-size: 1.25em">Retour Livré</span>
                                    </a>
                                    @break
                                @default
                                    <a style="color: white; background-color: rgb(124, 95, 204) !important;" class="badge badge-pill">
                                        <span style="font-size: 1.25em">{{App\Retour::where('commande_id', $commande->id)->first()->status}}</span>
                                    </a>
                                    @break
                            @endswitch

                        </td>
                            @can('edit-users')
                                <th>
                                    <a class=" text-muted waves-effect waves-dark pro-pic" href="{{route('admin.users.edit', $commande->livreur) }}"
                                        >
                                        <img src="{{App\User::where('id','168')->first()->image}}" alt="user" class="rounded-circle" width="31"> <br> {{App\User::where('id','168')->first()->name}}
                                    </a>
                                </th>
                            @endcan
                            <td>{{$commande->created_at}} <br> ({{\Carbon\Carbon::parse($commande->created_at)->diffForHumans()}})</td>
                            <td>{{$commande->updated_at}} <br> ({{\Carbon\Carbon::parse($commande->updated_at)->diffForHumans()}})</td>
                           <td style="font-size: 1.5em"><a title="Voir le detail" style="color: #467a0f" href="/commandes/{{$commande->id}}"><i class="mdi mdi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="text-align: center">Aucune commande enregistrée!</td>
                        </tr>

                           @endforelse

                        </tbody>

                    </table>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{$commandes ->appends($data)-> links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@cannot('livreur')
    <div class="container my-4">
        <div class="modal fade" id="modalSearchForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                            <h4 class="modal-title w-100 font-weight-bold">Rechercher sur les commandes</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body mx-3">
                                <form class="form-horizontal form-material" method="GET" action="{{route('retour.filter')}}">
                                    @csrf
                                    @can('manage-users')
                                    <div class="form-group row">
                                        <label for="client" class="col-sm-4">Fournisseur :</label>
                                        <div class="col-sm-8">
                                            <select name="client" id="client" class="form-control form-control-line" value="{{ old('client') }}">
                                                <option value="" disabled selected>Choisissez le fournisseur</option>
                                                @foreach ($clients as $client)
                                            <option value="{{$client->id}}" class="rounded-circle">
                                                {{$client->name}}
                                            </option>
                                                @endforeach

                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="livreur" class="col-sm-4">Livreur :</label>
                                        <div class="col-sm-8">
                                            <select name="livreur" id="livreur" class="form-control form-control-line" value="{{ old('livreur') }}">
                                                <option value="" disabled selected>Choisissez le livreur</option>
                                                @foreach ($livreurs as $livreur)
                                            <option value="{{$livreur->id}}" class="rounded-circle">
                                                {{$livreur->name}}
                                            </option>
                                                @endforeach

                                            </select>

                                        </div>
                                    </div>
                                    @endcan

                                    @cannot('livreur')
                                    <div class="form-group row">
                                        <label class="col-sm-4">Ville :</label>
                                        <div class="col-sm-8">
                                            <select name="ville" class="form-control form-control-line">
                                                <option selected disabled>Choisissez la ville</option>
                                                @foreach ($villes as $ville)
                                                <option value="{{$ville->name}}" class="rounded-circle">
                                                    {{$ville->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endcannot
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
@endcannot








@endsection

