@extends('racine')

@section('title')
   Argent et Caisse
@endsection

@section('content')


<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Argent et Caisse</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Caisse</li>
                    </ol>
                </nav>
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
                        <h4 class="card-title">Details pour chaque livreur</h4>
                    </div>

                </div>
                <!-- title -->
            </div>
            <div class="row" style="
            margin: 20px;
        ">
                <input class="form-control" id="myInput" type="text" placeholder="Rechercher...">

            </div>
            <div class="table-responsive">
                <table class="table v-middle">
                    <thead>
                        <tr class="bg-light">
                            @can('edit-users')
                            <th class="border-top-0">Nom du livreur</th>
                            <th class="border-top-0">Commandes Livrées</th>
                            <th class="border-top-0">Frais livreur</th>
                            <th class="border-top-0">Montant envoyé</th>
                            <th class="border-top-0">Reste</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody  id="myTable">
                        @foreach ($livreurs as $index => $livreur)
                        <tr >
                            <td>
                                <a href="{{route('caisse.livreur',['id'=> $livreur->id])}}" >
                                <span class="badge badge-pill badge-light">
                                    <h5 style="color: #666666;"  class="m-b-0 font-16">{{$livreur->name}}</h5>
                                </span>
                            </a>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-primary">
                                    <h5 class="m-b-0">
                                    {{$caisses[$index]}} MAD</h5>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-primary">
                                    <h5 class="m-b-0">
                                    {{$livraison[$index]}} MAD</h5>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-success">
                                    <h5 class="m-b-0">{{$envoyers[$index]}} MAD</h5>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-danger">
                                    <h5 class="m-b-0">
                                        {{$caisses[$index]- $livraison[$index] - $envoyers[$index]}} MAD
                                    </h5>
                                </span>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
