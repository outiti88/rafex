@extends('racine')

@section('title')
    Gestion des factures
@endsection




@section('style')
    <style>
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
        @if (session()->has('search'))
        <div class="alert alert-dismissible alert-warning col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>-</strong> Il n'existe aucun numero de facture avec : {{session()->get('search')}}  </a>.
          </div>
        @endif
        @if (session()->has('nbrCmdLivre'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Erreur ! </strong>vous ne pouvez pas charger la facture avec 0 commande livrée !
          </div>
        @endif
        @if (session()->has('nbrCmdRamasse'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Erreur ! </strong>vous ne pouvez pas charger la facture sans traiter tous les commandes ! <br>
        Il vous reste {{session()->get('nbrCmdRamasse')}} à traiter !
          </div>
        @endif

        @if (session()->has('facNoExist'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Erreur ! </strong>Cette facture à été déjà générée !
          </div>
        @endif

        @if (session()->has('ajoute'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Et voilà ! </strong>La facture à été bien généner !
          </div>
        @endif
        @if (session()->has('envoyer'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succés</strong> La facture à été bien envoyer à  : {{session()->get('envoyer')}}  </a>.
          </div>
        @endif
        <div class="col-5">
            <h4 class="page-title">Gestion des factures</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Facture</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
            <div class="text-right upgrade-btn">
                @can('ramassage-commande')
                <a  class="btn btn-danger text-white"  data-toggle="modal" data-target="#modalfacture"><i class="fa fa-plus-square">
                    </i> Générer la facture
                </a>
                @endcan
                    <a  class="btn btn-warning text-white"  data-toggle="modal" data-target="#modalFactureSearch"><i class="fa fa-search"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">{{ __('Total des Factures générées : ') }} {{ $total }}</div>

                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table table-hover" style="font-size: 0.9em;">
                            <thead>
                              <tr>
                                @can('ramassage-commande')
                                <th scope="col">#</th>
                                @endcan
                                <th scope="col">Code</th>
                                <th scope="col">Commandes livrées</th>
                                <th scope="col">Commandes non livrées</th>
                                <th scope="col">Montant Total</th>
                                <th scope="col">Frais de Livraison</th>
                                <th scope="col">Date d'ajout</th>
                                <th scope="col">Imprimer la facture</th>
                                @can('ramassage-commande')
                                <th scope="col">Envoyer par mail</th>
                                @endcan

                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($factures as $index => $facture)
                              <tr>
                                @can('ramassage-commande')
                                <th scope="row">
                                    <a title="{{$users[$index]->name}}" class=" text-muted waves-effect waves-dark pro-pic
                                        @if($users[$index]->statut)
                                            vip
                                        @endif

                                        "

                                                @can('edit-users')
                                                    href="{{route('admin.users.edit',$users[$index]->id)}}"
                                                @endcan

                                        >
                                        <img src="{{$users[$index]->image}}" alt="user" class="rounded-circle" width="31">
                                    </a>
                                </th>                                @endcan
                                <th>
                                    <a class="btn btn-light" href="{{route('facture.search',$facture->id)}}">
                                        {{$facture->numero}}
                                    </a>
                                </th>
                                <td>{{ $facture->livre}}</td>
                                <td>{{$facture->commande}}</td>
                                <td>{{ $facture->montant}} DH</td>
                                <td>{{ $facture->prix}} DH</td>
                                <td>{{ $facture->created_at}}</td>
                                <td>
                                    <a class="btn btn-warning text-white m-r-5" href="{{route('facture.gen',$facture->id)}}" target="_blank"><i class="mdi mdi-printer"></i></a>
                                </td>
                                @can('ramassage-commande')
                                <td>
                                    <a class="btn btn-info text-white m-r-5" href="{{route('email.facture',$facture->id)}}" ><i class="mdi mdi-send"></i></a>
                                </td>
                                @endcan
                            </tr>
                              @endforeach

                            </tbody>
                          </table>
                          <div class="row">
                            <div class="col-12 d-flex justify-content-center">
                                {{$factures ->appends($data)-> links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



<div class="container my-4">
    <div class="modal fade" id="modalfacture" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Choisissez le fournisseur</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="POST" action="{{route('facture.store')}}">
                                @csrf


                                <div class="form-group">
                                    <label for="client" class="col-sm-12">Fournisseur :</label>
                                    <div class="col-sm-12">
                                        <select name="client" id="client" class="form-control form-control-line" value="{{ old('client') }}" required>
                                            <option value="" disabled selected>Choisissez le fournisseur</option>
                                            @foreach ($clients as $client)
                                        <option value="{{$client->id}}" class="rounded-circle">
                                            {{$client->name}}
                                        </option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button class="btn btn-warning">Générer</button>

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


<div class="container my-4">
    <div class="modal fade" id="modalFactureSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Rechercher sur les Factures</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="GET" action="{{route('facture.filter')}}">
                                @csrf
                                @can('ramassage-commande')
                                <div class="form-group row">
                                    <label for="client" class="col-sm-4">Fournisseur :</label>
                                    <div class="col-sm-8">
                                        <select name="client" id="client" class="form-control form-control-line" value="{{ old('client') }}">
                                            <option value="" disabled selected>Choisissez le fournisseur</option>
                                            @foreach (App\User::whereHas('roles', function ($q) {
                                                $q->whereIn('name', ['client', 'ecom']);
                                            })->orderBy('name')->get() as $client)
                                        <option value="{{$client->id}}" class="rounded-circle">
                                            {{$client->name}}
                                        </option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>

                                @endcan

                                <div class="form-group row">
                                  <label for="date_facture_min" class="col-sm-4">Date Min :</label>
                                  <div class="col-sm-8">
                                    <input class="form-control" name="date_facture_min" type="date" id="date_facture_min">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="date_facture_max" class="col-sm-4">Date Max:</label>
                                  <div class="col-sm-8">
                                    <input class="form-control" name="date_facture_max" type="date" id="date_facture_max">
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



@endsection

@section('javascript')
    @if ($errors->any())
        <script type="text/javascript">
            $(window).on('load',function(){
                $('#modalSubscriptionForm').modal('show');
            });
        </script>
    @endif
@endsection
