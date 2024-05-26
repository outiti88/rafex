@extends('racine')

@section('title')
   Gestion des Secteurs
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
        <div class="col-5">
            <h4 class="page-title">Gestion des Secteur de la ville : {{$ville->name}}</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item"><a href="/ville">{{$ville->name}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Secteur</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
            <div class="text-right upgrade-btn">
            <a  class="btn btn-danger text-white"  data-toggle="modal" data-target="#FormStore"><i class="fa fa-plus-square"></i> Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="FormStore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Ajouter un nouveau secteur</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body mx-3">
              <form class="form-horizontal form-material" method="POST" action="{{route('ville.createSecteur')}}">
                  @csrf
                    <input type="hidden" name="ville_id" value="{{$ville->id}}">
                  <div id="education_fields">

                  </div>
                    <div class="row" id="test">

                        <div class="form-group col-md-12">
                          <label for="produit" class="col-sm-12">Nom du secteur :</label>
                          <div class="col-md-12">
                            <input  value="{{ old('name') }}" name="name" type="text" placeholder="Nom du secteur" class="form-control form-control-line" required>

                            </div>
                          </div>



                    </div>

                  <div class="form-group">
                      <label class="col-md-12">Prix:</label>
                      <div class="col-md-12">
                          <input  value="{{ old('prix') }}" name="prix" type="number" placeholder="Prix de livraison" class="form-control form-control-line" required>
                      </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-12">La part du livreur :</label>
                    <div class="col-md-12">
                        <input  value="{{ old('livreur') }}" name="livreur" type="number" placeholder="Part du livreur" class="form-control form-control-line" required>
                    </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12">Prix du refusé :</label>
                  <div class="col-md-12">
                      <input  value="{{ old('refuse') }}" name="refuse" type="number" placeholder="Prix du refusé" class="form-control form-control-line" required>
                  </div>
              </div>


                  <div class="form-group">
                      <div class="modal-footer d-flex justify-content-center">
                          <button class="btn btn-danger">Ajouter</button>

                      </div>
                  </div>
              </form>

          </div>

        </div>
      </div>
</div>

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Total des secteurs: {{ $total }} secteurs</div>
                <input class="form-control" id="myInput" type="text" placeholder="Rechercher...">


                <div class="card-body" >
                    <div class="table-responsive">
                        <table class="table" >
                            <thead>
                              <tr>
                                <th scope="col">Secteur</th>
                                <th scope="col">Prix</th>
                                <th scope="col">Part de Livreur</th>
                                <th scope="col">Prix du refusé</th>

                                @can('edit-users')
                                <th scope="col">Action</th>
                                @endcan

                              </tr>
                            </thead>

                            <tbody id="myTable">
                                @foreach ($secteurs as $secteur)
                              <tr>

                                <td>{{$secteur->name}}</td>
                                <td>{{$secteur->prix}}</td>
                                <td>{{$secteur->livreur}}</td>
                                <td>{{$secteur->refuse}}</td>

                                @can('edit-users')
                                <td>
                                    <a style="color: white" class="btn btn-primary float-lef"  data-toggle="modal" data-target="#FormEdit{{$secteur->id}}">
                                       <i class="mdi mdi-account-edit"></i>
                                   </a>

                                   <div class="modal fade" id="FormEdit{{$secteur->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header text-center">
                                            <h4 class="modal-title w-100 font-weight-bold">Modifier le secteur {{$secteur->name}}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body mx-3">
                                              <form class="form-horizontal form-material" method="POST" action="{{route('ville.updateSecteur',$secteur->id)}}">
                                                  @csrf
                                                  @method("PUT")

                                                  <div id="education_fields">

                                                  </div>
                                                    <div class="row" id="test">

                                                        <div class="form-group col-md-12">
                                                          <label for="produit" class="col-sm-12">Nom du secteur :</label>
                                                          <div class="col-md-12">
                                                            <input  value="{{$secteur->name}}" name="name" type="text" placeholder="Nom du secteur" class="form-control form-control-line" required>

                                                            </div>
                                                          </div>



                                                    </div>
                                                  <div class="form-group">
                                                      <label class="col-md-12">Prix:</label>
                                                      <div class="col-md-12">
                                                          <input  value="{{$secteur->prix}}" name="prix" type="number" placeholder="Prix de livraison" class="form-control form-control-line" required>
                                                      </div>
                                                  </div>

                                                    <div class="form-group">
                                                        <label class="col-md-12">La part du livreur :</label>
                                                        <div class="col-md-12">
                                                            <input  value="{{$secteur->livreur}}" name="livreur" type="number" placeholder="Part du livreur" class="form-control form-control-line" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-12">Prix du refusé :</label>
                                                        <div class="col-md-12">
                                                            <input  value="{{$secteur->refuse}}" name="refuse" type="number" placeholder="Prix du refusé" class="form-control form-control-line" required>
                                                        </div>
                                                    </div>

                                                  <div class="form-group">
                                                      <div class="modal-footer d-flex justify-content-center">
                                                          <button class="btn btn-danger">Modifier</button>

                                                      </div>
                                                  </div>
                                              </form>

                                          </div>

                                        </div>
                                      </div>
                                  </div>







                                <a class="btn btn-danger text-white m-r-5" data-toggle="modal" data-target="#FormDelete{{$secteur->id}}"><i class="fas fa-trash-alt"></i></a>

                                <div class="modal fade" id="FormDelete{{$secteur->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">êtes-vous sur de vouloir supprimer ce secteur ?</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>
                                                secteur: {{$secteur->name}}
                                            </h5>
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

                                            <form action="{{route('ville.destroySecteur',$secteur->id)}}" method="POST" class="float-left">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="btn btn-danger text-white m-r-5">Ok</button>
                                            </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>


                               </td>
                                @endcan

                                </tr>
                              @endforeach

                            </tbody>
                          </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
