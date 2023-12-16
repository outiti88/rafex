@extends('racine')

@section('title')
    Bon de Livraison
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
        <strong>-</strong> Il n'existe aucun numero de bon de commande avec : {{session()->get('search')}}  </a>.
          </div>
        @endif
        @if (session()->has('cmdExist'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Erreur ! </strong>vous ne pouvez pas charger le bon de livraison avec 0 commande ramassée !
          </div>
        @endif
        @if (session()->has('blNoExist'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Erreur ! </strong>Le bon de livraison d'aujourd'hui à été déjà générer !
          </div>
        @endif
        @if (session()->has('ajoute'))
        <div class="alert alert-dismissible alert-success col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Et voilà ! </strong>Le bon de livraison d'aujourd'hui à été bien générer !
          </div>
        @endif

        <div class="col-5">
            <h4 class="page-title">Bon de livraison</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Cavallo</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bon de livraison</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
            <div class="text-right upgrade-btn">
                @can('ramassage-commande')
                <a  class="btn btn-danger text-white"  data-toggle="modal" data-target="#modalBonLivraison"><i class="fa fa-plus-square">
                    </i> Générer le bon de livraison
                </a>

                </select>
                @endcan
                @cannot('ramassage-commande')

                    <button data-toggle="modal" data-target="#genererBon"type="submit" class="btn btn-danger text-white m-r-5"><i class="fa fa-plus-square"></i> Générer le bon de livraison</button>
                @endcan
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">{{ __('Total des Bons de livraison : ') }} {{ $total }}</div>

                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                              <tr>
                                @can('ramassage-commande')
                                <th scope="col">#</th>
                                @endcan
                                <th scope="col">Code</th>
                                <th scope="col">Commandes ramassées</th>
                                <th scope="col">Nombre de Colis</th>
                                <th scope="col">Date d'ajout</th>
                                <th scope="col">Montant Total</th>
                                <th scope="col">Frais de Livraison</th>
                                <th scope="col">Imprimer</th>

                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($bonLivraisons as $index => $bonLivraison)
                              <tr
                              @if (\Carbon\Carbon::parse($bonLivraison->created_at)->format('d-m-Y') == date("d-m-Y") && session()->has('bonLivraison') )
                              style="background-color: #467a0f2e;"
                              @endif
                              >
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
                                  <a class="btn btn-light" href="{{route('bon.search',$bonLivraison->id)}}">
                                    BL_{{bin2hex(substr($users[$index]->name, - strlen($users[$index]->name) , 3)).$bonLivraison->id}}
                                  </a>

                                </th>
                                <td>{{$bonLivraison->commande}}</td>
                                <td>{{$bonLivraison->colis}}</td>
                                <td>{{ $bonLivraison->created_at}}</td>
                                <td>{{ $bonLivraison->montant}} DH</td>
                                <td>{{ $bonLivraison->prix}} DH</td>
                                <td>
                                <a target="_blank" class="btn btn-info text-white m-r-5" href="{{route('bon.gen',$bonLivraison->id)}}" >
                                  <i class="fas fa-print"></i></a>
                               </td>

                            </tr>
                              @endforeach

                            </tbody>
                          </table>
                          <div class="row">
                            <div class="col-12 d-flex justify-content-center">
                                {{$bonLivraisons ->appends($data)-> links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



<div class="container my-4">
    <div class="modal fade" id="modalBonLivraison" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                            <form class="form-horizontal form-material" method="POST" action="{{route('bonlivraison.store')}}">
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

                                 <!--  <label for="ville" class="col-sm-12">Ville :</label>
                                  <div class="col-sm-12">
                                      <select value="{{ old('ville') }}" name="ville" class="form-control form-control-line" id="ville" required>
                                          <option checked>Choisissez la ville</option>
                                          <option value="Agadir"> Agadir</option>
                                          <option value="Al Hoceima"> Al Hoceima</option>
                                          <option value="Béni Mellal"> Béni Mellal</option>
                                          <option value="Casablanca">Casablanca</option>
                                          <option value="El Jadida"> El Jadida</option>
                                          <option value="Errachidia"> Errachidia</option>
                                          <option value="Fès"> Fès</option>
                                          <option value="Khénifra"> Khénifra</option>
                                          <option value="Khouribga"> Khouribga</option>
                                          <option value="Kénitra">Kénitra</option>
                                          <option value="Larache"> Larache</option>
                                          <option value="Marrakech">Marrakech</option>
                                          <option value="Meknès"> Meknès</option>
                                          <option value="Nador"> Nador</option>
                                          <option value="Ouarzazate"> Ouarzazate</option>
                                          <option value="Oujda"> Oujda</option>
                                          <option value="Rabat"> Rabat</option>
                                          <option value="Safi"> Safi</option>
                                          <option value="Settat"> Settat</option>
                                          <option value="Salé"> Salé</option>
                                          <option value="Tanger"> Tanger</option>
                                          <option value="Taza"> Taza</option>
                                          <option value="Tétouan"> Tétouan</option>
                                      </select>
                                  </div> -->
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



<!-- Modal -->
<div class="modal fade" id="genererBon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Êtes-vous sûr de générer le bon de livraison pour ce jour?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Vous avez {{$ramasse}} de commandes Rammassées sur {{$nonRamasse + $ramasse}} commandes envoyées
        </div>
        <div class="modal-body">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                NB: Aprés la génération du bon de livraison vous ne pourrez pas <strong>ajouter une nouvelle commande</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
          <form method="POST" action="{{ route('bonlivraison.store') }}">
            @csrf
            <button data-toggle="modal" data-target="#genererBon"type="submit" class="btn btn-danger text-white m-r-5">générer</button>
        </form>
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
