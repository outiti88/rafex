@extends('racine')

@section('title')
Bon de retour | {{$bonretour->reference}}
@endsection



@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-12">
        <h4 class="page-title">Gestion des transfert des retours {{$bonretour->reference}}</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="/transfert-retour">Bon de retour</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$bonretour->reference}}</li>

                    </ol>
                </nav>
            </div>
            <div class="row float-right" id="navbar-example3">
                <a target="_blank" class="btn btn-info text-white m-r-5" href="{{route('transfert.retour.pdf',$bonretour->id)}}" >
                {{-- <a target="_blank" class="btn btn-info text-white m-r-5" href="" > --}}
                    <i class="fas fa-print"></i></a>
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
                                        <h5><span style="font-weight: 900">Date de la demande de transfert des retours :</span> {{$bonretour->created_at}}</h5>
                                        <h5><span style="font-weight: 900">Expéditeur :</span> {{$bonretour->city}}</h5>
                                    </div>
                                    <div class="col-6">
                                        <h5><span style="font-weight: 900">Envoyée par :</span> {{App\User::where('id',$bonretour->user_id)->first()->name}}</h5>
                                        <h5><span style="font-weight: 900">Livreur :</span> {{App\User::where('id',$bonretour->livreur_id)->first()->name}}</h5>
                                    </div>

                                </div>
                              <h4>Les commandes liées à cette demande de transfert des retours</h4>
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
                                    <th>ville du fournisseur</th>
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
                                    <td>{{$commande->user()->first()->ville}}</td>
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


</div>


@endsection

@section('javascript')



@endsection

