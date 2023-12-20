@extends('racine')

@section('title')
   Gestion des réclamations
@endsection

@section('content')


<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Gestion des réclamations</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Réclamations</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
            <div class="row float-right d-flex ">
                <div class="m-r-5" style="margin-right: 10px;">
                    <a  class="btn btn-warning text-white"  data-toggle="modal" data-target="#modalSearchForm"><i class="fa fa-search"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- column -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- title -->
                <div class="d-md-flex align-items-center">
                    <div>
                        <h4 class="card-title">Gestion des réclamations</h4>
                    </div>

                </div>
                <!-- title -->
            </div>
            @if (session()->has('traiter'))
            <div class="alert alert-dismissible alert-success col-12">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Succés !</strong> La Réclamation pour l'objet "{{session()->get('traiter')}}"  à été bien Traitée </a>.
              </div>
            @endif
            @if (session()->has('ajouter'))
            <div class="alert alert-dismissible alert-success col-12">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Succés !</strong> Votre Réclamation à été bien Envoyée </a>.
              </div>
            @endif
            <div class="row" style="
            margin: 20px;
        ">
                <input class="form-control" id="myInput" type="text" placeholder="Rechercher...">

            </div>
            <div class="table-responsive">
                <table class="table v-middle">
                    <thead>
                        <tr class="bg-light">
                            @can('manage-users')
                            <th class="border-top-0">Nom du Fournisseur</th>
                            @endcan
                            <th class="border-top-0">Numéro de la commmande</th>
                            <th class="border-top-0">Objet</th>
                            <th class="border-top-0">Réclamation</th>
                            <th class="border-top-0">Statut</th>
                            <th class="border-top-0">Date de création</th>
                        </tr>
                    </thead>
                    <tbody  id="myTable">


                        @forelse ($reclamations as $index => $reclamation)
                        <tr >
                            @can('manage-users')
                            <td>
                                <a @can('edit-users')
                                    href="{{route('admin.users.edit',$fournisseurs[$index]->id)}}"
                                    @endcan >
                                    <h5 style="color: #666666;"  class="m-b-0 font-16">{{$fournisseurs[$index]->name}}</h5>
                            </a>
                            </td>
                            @endcan
                            <td>
                                <a href="/commandes/{{$commandes[$index]->id}}" >
                                    <h5 style="color: #666666;"  class="m-b-0 font-16">{{$commandes[$index]->numero}}</h5>
                            </a>

                            </td>
                            <td>
                                    <h5 class="m-b-0">
                                        {{$reclamation->objet}}</h5>
                            </td>
                            <td>
                                    <p class="m-b-0">{{$reclamation->description}}</p>
                            </td>
                            <td>
                                @if ($reclamation->etat == 0)
                                <a @can('manage-users')
                                href="/reclamation/{{$reclamation->id}}"
                                    @endcan >
                                <span class="badge badge-pill badge-info" style="color: white">
                                    <h5 class="m-b-0">
                                        En cours de traitement
                                    </h5>
                                </span>
                                 </a>
                                @else
                                <span class="badge badge-pill badge-success">
                                    <h5 class="m-b-0">
                                        Réclamation traitée
                                    </h5>
                                </span>
                                @endif
                                <td>
                                    <h5 class="m-b-0">
                                        {{$reclamation->created_at}}</h5>
                            </td>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="text-align: center">Aucune Réclamation enregistrée!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        {{$reclamations ->appends($data)-> links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container my-4">
    <div class="modal fade" id="modalSearchForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header text-center">
                          <h4 class="modal-title w-100 font-weight-bold">Rechercher sur les Réclamations</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body mx-3">
                            <form class="form-horizontal form-material" method="GET" action="{{route('reclamation.filter')}}">
                                @csrf
                                @can('manage-users')
                                <div class="form-group row">
                                    <label for="client" class="col-sm-4">Fournisseur :</label>
                                    <div class="col-sm-8">
                                        <select name="fournisseur" id="client" class="form-control form-control-line" value="{{ old('client') }}">
                                            <option value="" disabled selected>Choisissez le fournisseur</option>
                                            @foreach ($clients as $client)
                                        <option value="{{$client->id}}" class="rounded-circle">
                                            {{$client->name}}
                                        </option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>
                                @endcan


                                  <div class="form-group row">
                                    <label class="col-sm-4">Etat de la réclamation :</label>
                                    <div class="col-sm-8">
                                        <select name="etat" class="form-control form-control-line" >
                                            <option value="0">En cours de traitement</option>
                                            <option value="1">Taitée</option>
                                        </select>
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
