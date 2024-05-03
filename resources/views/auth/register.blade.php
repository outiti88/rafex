@extends('racine')


@section('title')
    Nouveau Utilisateur
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Gestion des Utilisateurs</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item"><a href="/">Utilisateurs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ajouter</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>



<div class="container-fluid">
        <div class="alert alert-dismissible alert-warning col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Si l'utilisateur est un client c'est recommander de laisser le mot de passe par defaut : Cavallo2020 </strong>.
        </div>
    <div class="row justify-content-center">

        <div class="col-md-10">

            <div class="card">
                <div class="card-header">
                    Ajouter un nouveau utilisateur: ( {{$roleOfUser}} )
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('register') }}"  enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <label for="roles" class="col-md-12 col-form-label text-center font-bold font-16">Informations Utilisateur: </label>
                        </div>
                        <div class="form-roles-container">
                            @switch($roleOfUser)
                                @case('admin')
                                    <input type="hidden" name="roles[]" value="1" id="admin" checked>
                                    @include('auth.partiels._admin', ['isUpdate' => false])
                                    @break
                                @case('personnel')
                                    <input type="hidden" name="roles[]" value="4" id="personnel" checked>
                                    @include('auth.partiels._personnel', ['isUpdate' => false])
                                    @break
                                @case('stock')
                                    <input type="hidden" name="roles[]" value="5" id="stock" checked>
                                    @include('auth.partiels._stock', ['isUpdate' => false])
                                    @break
                                @case('ramassage')
                                    <input type="hidden" name="roles[]" value="2" id="ramassage" checked>
                                    @include('auth.partiels._ramassage', ['isUpdate' => false])
                                    @break
                                @case('livreur')
                                    <input type="hidden" name="roles[]" value="3" id="livreur" checked >
                                    @include('auth.partiels._livreur', ['isUpdate' => false])
                                    @break
                                @case('superviseur')
                                    <input type="hidden" name="roles[]" value="7" id="superviseur" checked >
                                    @include('auth.partiels._superviseur', ['isUpdate' => false])
                                    @break
                            @endswitch
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-2 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-4">
                                <input id="password" value="Cavallo2020" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="password-confirm" class="col-md-2 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-4">
                                <input id="password-confirm"  value="Cavallo2020"  type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 d-flex justify-content-around ">
                                <button type="submit" class="btn btn-primary" style="width: 30%;">
                                    {{ __('Ajouter') }}
                                </button>
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


@endsection
