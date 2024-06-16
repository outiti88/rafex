@extends('racine')

@section('title')
Bons des retours
@endsection

@section('style')
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Gestion des bons des retours</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bon des retours</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-7">
        <div class="row float-right d-flex ">
            <div class="m-r-5" style="margin-right: 10px;">
                <a  class="btn btn-warning text-white"  data-toggle="modal" data-target="#modalTransfertSearch"><i class="fa fa-search"></i></a>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gérer vos bons de retours</h4>
                    <h6 class="card-subtitle">Nombre total des bons : <code>{{$total}}</code> .</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" style="font-size: 0.85em;">
                        <thead>
                            <tr>
                                <th scope="col">Référence</th>
                                <th scope="col">Livreur</th>
                                <th scope="col">Ville</th>
                                <th scope="col">Date d'ajout</th>
                                <th scope="col">Nombre de colis</th>
                                <th scope="col">Crée Par</th>
                                <th scope="col">Détail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bon_retours as $index => $bon_retour)
                                <tr>
                                    <td>{{$bon_retour->reference}}</td>
                                    <td> <img src="{{App\User::where('id',$bon_retour->livreur_id)->first()->image}}" alt="user" class="rounded-circle" width="31"> <br> {{App\User::where('id',$bon_retour->livreur_id)->first()->name}}</td>
                                    <td>{{$bon_retour->city}}</td>
                                    <td>{{$bon_retour->created_at}}</td>
                                    <td>{{$bon_retour->orders}}</td>
                                    <td> <img src="{{App\User::where('id',$bon_retour->user_id)->first()->image}}" alt="user" class="rounded-circle" width="31"> <br> {{App\User::where('id',$bon_retour->user_id)->first()->name}}</td>

                                    <td style="font-size: 1.5em">
                                        <a style="color: #467a0f" href="/bonretour/{{$bon_retour->id}}">
                                            <i class="ti-pencil"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align: center">Aucun bon de retour enregistrée!</td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{$bon_retours->appends($data)-> links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection

@section('javascript')
@endsection
