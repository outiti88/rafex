@extends('racine')

@section('title')
Ramassage | {{$ramassage->reference}}
@endsection



@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-12">
        <h4 class="page-title">Gestion du ramassage {{$ramassage->reference}}</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="/ramassage">Ramassage</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$ramassage->reference}}</li>

                    </ol>
                </nav>
            </div>
            <div class="row float-right" id="navbar-example3">
                @can('admin-superviseur')
                <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalSubscriptionFormLivreur"><i class="fas fa-user"></i> <span class="quick-action"> Affecter</span></a>
                @endcan
                @if ($ramassage->statut === "En attente de ramassage")
                    @can('admin-superviseur-livreur')
                    <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalRelance"><i class="fas fa-random"></i> <span class="quick-action">Valider </span></a>
                    @endcan
                @endif
                @if ($ramassage->statut === "Ramassé par le livreur")
                    @can('admin-superviseur')
                    <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalRelance"><i class="fas fa-random"></i> <span class="quick-action">Valider </span></a>
                    @endcan
                @endif
                <a  class="btn btn-success text-white m-r-5" href="{{ route('bon.infos',['id'=> $ramassage->bonlivraison()->first()->id ]) }}"><i class="fas fa-print"></i> <span class="quick-action">Bon de livraison </span></a>
            </div>
        </div>

      </div>

</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->


<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <div class="row">

        @if (session()->has('ramassage-validated'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Succés !</strong> Ramassage Validé </a>.
          </div>
        @endif
        @if (session()->has('ramassage-already-validated'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oups !</strong> Ramassage déjà Validé </a>.
          </div>
        @endif
    </div>    <!-- ============================================================== -->

    <div class="row">
      <div class="col-lg-12 col-xlg-3 col-md-5">
          <div class="card">
              <div class="card-body">
              <center class="m-t-30">
                  <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="table-responsive" style="border-top: solid;padding-top: 10px;">
                                <div class="row" style="text-align: left;border-bottom: solid;margin-bottom: 20px;">
                                    <div class="col-6">
                                        <h5><span style="font-weight: 900">Date de la demande de ramassage :</span> {{$ramassage->created_at}}</h5>
                                        <h5><span style="font-weight: 900">Ville de ramassage :</span> {{$ramassage->city}}</h5>
                                        <h5><span style="font-weight: 900">Adresse de ramassage :</span> {{$ramassage->adress}}</h5>
                                    </div>
                                    <div class="col-6">
                                        @if ($ramassage->livreurId != null)
                                        <h5><span style="font-weight: 900">Ramasseur :</span> {{App\User::where('id',$ramassage->livreurId)->first()->name}}</h5>
                                        @else
                                        <h5 style="color: red">Aucun ramasseur est affecté</h5>
                                        @endif
                                        <h5><span style="font-weight: 900">Statut :</span> {{$ramassage->statut}}</h5>
                                        <h5><span style="font-weight: 900">Satut modifié par :</span>
                                            @if($ramassage->statusUpdatedBy)
                                            {{App\User::where('id',$ramassage->statusUpdatedBy)->first()->name}}
                                            @else
                                            {{App\User::where('id',$ramassage->user_id)->first()->name}}
                                            @endif
                                        </h5>
                                    </div>

                                </div>
                              <h4>Les commandes liées à cette demande de ramassage</h4>
                              <table class="table table-striped" id="table-1">
                                <thead>
                                  <tr>
                                    <th class="text-center">
                                      #
                                    </th>
                                    <th>Commande</th>
                                    <th>Date de la commande</th>
                                    <th>Status</th>
                                    <th>ville de livraison</th>
                                    <th>Date du demande de ramassage</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @forelse ($commandes as $index => $commande)
                                  <tr>
                                    <td>
                                      {{$index}}
                                    </td>
                                    <td>
                                        <a href="/commandes/{{$commande->id}}">
                                            {{$commande->numero}}
                                        </a>
                                    </td>

                                    <td>{{$commande->created_at}}</td>
                                    <td>{{$commande->statut}}</td>
                                    <td>{{$commande->ville}}</td>
                                    <td>{{$ramassage->prevu_at}}</td>
                                  </tr>
                                  @empty

                                  @endforelse

                                </tbody>
                              </table>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
              </center>
              </div>

          </div>
      </div>

  </div>

    <div class="modal fade" id="modalRelance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de valider cette demande de ramassage ?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <h5>
                    Référence de la demande: {{$ramassage->reference}}
                </h5>
                <p class="proile-rating">Statut : {{$ramassage->statut}}</p>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Cliquez sur <b>Ok</b> pour confirmer ou <b>fermer</b> pour annuler la validation

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            <form method="GET" action="{{ route('ramassage.validate',['id'=> $ramassage->id]) }}">
                @csrf
                <button type="submit" class="btn btn-primary text-white m-r-5">Ok</button>
            </form>
            </div>
        </div>
        </div>
</div>


</div>



<div class="modal fade" id="modalSubscriptionFormLivreur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form  method="POST" action="{{route('ramassage.livreur',['id' => $ramassage->id])}}">
            @csrf
            @method('PATCH')
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Choisissez le livreur au quel vous voulez affecter cette demande de ramassage ?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <h5>
                Vendeur :  {{$ramassage->user()->first()->name}}
            </h5>
            <h5>
                Ville de Ramsasage: {{$ramassage->city}}
            </h5>
            <h6>
                Adresse de ramassage : {{$ramassage->adress}}
            </h6>
            <div class="form-group row">
                <label for="livreur" class="col-sm-4">Livreur :</label>
                <div class="col-sm-8">
                    <select name="livreurId" id="livreur" class="form-control form-control-line" value="{{ old('livreurId') }}">
                        <option value=""  selected >Choisissez le livreur</option>
                        @foreach ($livreurs as $livreur)
                            <option value="{{$livreur->id}}" class="rounded-circle" @if ($livreur->id == $ramassage->livreurId) selected @endif >
                                {{$livreur->name}}
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

@endsection

@section('javascript')



@endsection

