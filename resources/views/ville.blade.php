
@extends('racine')

@section('title')
   Gestion des Villes
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
            <h4 class="page-title">Gestion des Villes</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Villes</li>
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
            <h4 class="modal-title w-100 font-weight-bold">Ajouter une nouvelle ville</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body mx-3">
              <form class="form-horizontal form-material" method="POST" action="{{route('ville.store')}}">
                  @csrf

                  <div id="education_fields">

                  </div>
                    <div class="row" id="test">

                        <div class="form-group col-md-12">
                          <label for="produit" class="col-sm-12">Nom de la ville :</label>
                          <div class="col-md-12">
                            <input  value="{{ old('name') }}" name="name" type="text" placeholder="Nom de la ville" class="form-control form-control-line" required>

                            </div>
                          </div>



                    </div>

                  <div class="form-group">
                      <label class="col-md-12">Prix :</label>
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
                <div class="card-header">Total des villes: {{ $total }} Villes</div>
                <input class="form-control" id="myInput" type="text" placeholder="Rechercher...">


                <div class="card-body" >
                    <div class="table-responsive">
                        <table class="table" >
                            <thead>
                              <tr>
                                <th scope="col">Ville</th>
                                <th scope="col">Prix</th>
                                <th scope="col">Part de Livreur</th>
                                <th scope="col">Prix du refusé</th>

                                @can('edit-users')
                                <th scope="col">Action</th>
                                @endcan

                              </tr>
                            </thead>

                            <tbody id="myTable">
                                @foreach ($villes as $ville)
                              <tr>

                                <td>{{$ville->name}}</td>
                                <td>{{$ville->prix}}</td>
                                <td>{{$ville->livreur}}</td>
                                <td>{{$ville->refuse}}</td>

                                @can('edit-users')
                                <td>
                                    <a style="color: white" class="btn btn-primary float-lef"  data-toggle="modal" data-target="#FormEdit{{$ville->id}}">
                                       <i class="mdi mdi-account-edit"></i>
                                   </a>

                                   <div class="modal fade" id="FormEdit{{$ville->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header text-center">
                                            <h4 class="modal-title w-100 font-weight-bold">Modifier la ville {{$ville->name}}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body mx-3">
                                              <form class="form-horizontal form-material" method="POST" action="{{route('ville.updateVille',$ville->id)}}">
                                                  @csrf
                                                  @method("PUT")

                                                  <div id="education_fields">

                                                  </div>
                                                    <div class="row" id="test">

                                                        <div class="form-group col-md-12">
                                                          <label for="produit" class="col-sm-12">Nom de la ville :</label>
                                                          <div class="col-md-12">
                                                            <input  value="{{$ville->name}}" name="name" type="text" placeholder="Nom de la ville" class="form-control form-control-line" required>

                                                            </div>
                                                          </div>



                                                    </div>

                                                  <div class="form-group">
                                                      <label class="col-md-12">Prix :</label>
                                                      <div class="col-md-12">
                                                          <input  value="{{$ville->prix}}" name="prix" type="number" placeholder="Prix de livraison" class="form-control form-control-line" required>
                                                      </div>
                                                  </div>

                                                    <div class="form-group">
                                                        <label class="col-md-12">La part du livreur :</label>
                                                        <div class="col-md-12">
                                                            <input  value="{{$ville->livreur}}" name="livreur" type="number" placeholder="Part du livreur" class="form-control form-control-line" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-12">Prix du refusé :</label>
                                                        <div class="col-md-12">
                                                            <input  value="{{$ville->refuse}}" name="refuse" type="number" placeholder="Prix du refusé" class="form-control form-control-line" required>
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







                                <a class="btn btn-danger text-white m-r-5" data-toggle="modal" data-target="#FormDelete{{$ville->id}}"><i class="fas fa-trash-alt"></i></a>

                                <div class="modal fade" id="FormDelete{{$ville->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">etes-vous sur de vouloir supprimer cette ville ?</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                            <h5>
                                                ville: {{$ville->name}}
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

                                            <form action="{{route('ville.destroy',$ville->id)}}" method="POST" class="float-left">
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

@section('javascript')
    @if ($errors->any())
        <script type="text/javascript">
            $(window).on('load',function(){
                $('#modalSubscriptionForm').modal('show');
            });
        </script>
    @endif
    <script>
      $(document).ready(function(){
        $("#myInput").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
      </script>
@endsection
