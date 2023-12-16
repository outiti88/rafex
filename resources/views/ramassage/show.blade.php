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
                        <li class="breadcrumb-item"><a href="/">Cavallo</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="/ramassage">Ramassage</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$ramassage->reference}}</li>

                    </ol>
                </nav>
            </div>
            <div class="row float-right" id="navbar-example3">
                @if ($ramassage->statut === "En attente")
                    @can('ramassage-commande')
                    <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalRelance"><i class="fas fa-random"></i> <span class="quick-action">Valider </span></a>
                    @endcan
                @endif
                @if ($ramassage->statut === "Ramassé par le livreur")
                    @can('manage-users')
                    <a  class="btn btn-success text-white m-r-5" data-toggle="modal" data-target="#modalRelance"><i class="fas fa-random"></i> <span class="quick-action">Valider </span></a>
                    @endcan
                @endif
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
    </div>    <!-- ============================================================== -->

    @can("manage-users")
    <div class="row">
      <div class="col-lg-12 col-xlg-3 col-md-5">
          <div class="card">
              <div class="card-body">
              <center class="m-t-30">
                  <div class="row">
                      <div class="col-12">
                        <div class="card">
                          {{-- <div class="card-header">
                            <h2>{{$ramassage->reference}}</h2>
                          </div> --}}
                          <div class="card-body">
                            <div class="table-responsive" style="border-top: solid;padding-top: 10px;">
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
                                    <th>Date du demande de ramassage</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @forelse ($commandes as $index => $commande)
                                  <tr>
                                    <td>
                                      {{$index}}
                                    </td>
                                    <td>{{$commande->numero}}</td>

                                    <td>{{$commande->created_at}}</td>
                                    <td>{{$commande->statut}}</td>
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
    @endcan

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
@endsection

@section('javascript')



@endsection

