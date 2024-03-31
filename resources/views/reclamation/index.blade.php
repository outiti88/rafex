@extends('racine')

@section('title')
Gestion des Tickets
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Gestion des Tickets</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tickets</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="row float-right d-flex ">
                    <div class="m-r-5" style="margin-right: 10px;">
                        <a class="btn btn-warning text-white" data-toggle="modal" data-target="#modalSearchForm"><i
                                class="fa fa-search"></i></a>
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
                            <h4 class="card-title">Nombre total des tickets :  {{$total}}</h4>
                        </div>

                    </div>
                    <!-- title -->
                </div>
                @if (session()->has('traiter'))
                <div class="alert alert-dismissible alert-success col-12">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Succés !</strong> Le ticket a été bien fermé </a>.
                </div>
                @endif
                @if (session()->has('commentAdded'))
                <div class="alert alert-dismissible alert-success col-12">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Succés !</strong> Commentaire ajouté pour le ticket </a>.
                </div>
                @endif
                @if (session()->has('ajouter'))
                <div class="alert alert-dismissible alert-success col-12">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Succés !</strong> Votre Ticket a été bien ouvert </a>.
                </div>
                @endif
                @include('reclamation._comments', ['from' => 'reclamation'])
            </div>
        </div>
    </div>


    <div class="container my-4">
        <div class="modal fade" id="modalSearchForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Rechercher sur les Tickets</h4>
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
                                    <select name="fournisseur" id="client" class="form-control form-control-line"
                                        value="{{ old('client') }}">
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
                                <label class="col-sm-4">Statut du ticket :</label>
                                <div class="col-sm-8">
                                    <select name="etat" class="form-control form-control-line">
                                        <option value="" disabled selected>Choisissez le statut</option>
                                        <option value="0">Ticket ouvert</option>
                                        <option value="1">Ticket fermé</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4">Objet du ticket :</label>
                                <div class="col-sm-8">
                                    <select name="objet" class="form-control form-control-line">
                                        <option value="" disabled selected>Choisissez l'objet</option>
                                        <option value="Livraison">Livraison</option>
                                        <option value="Retour"  >Retour</option>
                                        <option value="Retour de fond"  >Retour de fond</option>
                                        <option value="Modification de colis">Modification de colis</option>
                                        <option value="Réclamation"  >Réclamation</option>
                                        <option value="Autres"  >Autres</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="modal-footer d-flex justify-content-center">
                                    <button type="submit" class="btn btn-warning"><i class="fa fa-search"></i>
                                        Rechercher</button>

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
        $(document).ready(function () {
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });


        function loadFile(event, reclamationId) {
            var image2 = document.getElementById('output'+reclamationId);
            image2.src = URL.createObjectURL(event.target.files[0]);
        };

        function loadFile2(event, reclamationId) {
            var image2 = document.getElementById('output'+reclamationId);
            image2.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endsection
