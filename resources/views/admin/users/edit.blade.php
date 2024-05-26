
@extends('racine')

@section('title')
   Gestion des Utilisateurs
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

        body {
  background: whitesmoke;
  font-family: 'Open Sans', sans-serif;
}
    .container-image-profil {
    max-width: 960px;
    margin: 30px auto;
    padding: 20px;
    }
    h1 {
    font-size: 20px;
    text-align: center;
    margin: 20px 0 20px;
    }
    h1 small {
    display: block;
    font-size: 15px;
    padding-top: 8px;
    color: gray;
    }
    .avatar-upload {
    position: relative;
    max-width: 205px;
    margin: 50px auto;
    }
    .avatar-upload .avatar-edit {
    position: absolute;
    right: 12px;
    z-index: 1;
    top: 10px;
    }
    .avatar-upload .avatar-edit input {
    display: none;
    }
    .avatar-upload .avatar-edit input + label {
        display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    margin-bottom: 0;
    border-radius: 100%;
    background: #FFFFFF;
    border: 1px solid transparent;
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
    cursor: pointer;
    font-weight: normal;
    transition: all 0.2s ease-in-out;
    }
    .avatar-upload .avatar-edit input + label:hover {
    background: #f1f1f1;
    border-color: #d6d6d6;
    }
    .avatar-upload .avatar-edit input + label:after {
    color: #757575;
    position: absolute;
    top: 10px;
    left: 0;
    right: 0;
    text-align: center;
    margin: auto;
    }
    .avatar-upload .avatar-preview {
    width: 192px;
    height: 192px;
    position: relative;
    border-radius: 100%;
    border: 6px solid #F8F8F8;
    box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }
    .avatar-upload .avatar-preview > div {
    width: 100%;
    height: 100%;
    border-radius: 100%;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    }

    </style>
@endsection


@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Gestion des Utilisateurs </h4>
            {{$user->roles()->get()->pluck('name')->toArray()[0]}}
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Rafex</a></li>
                        <li class="breadcrumb-item"><a href="/admin/users">Utilisateurs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$user->name}}</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">


                <div class="card-body">
                <form method="POST" action="{{route('admin.users.update',$user)}}" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    @if ($user->roles()->get()->pluck('name')->toArray()[0] == 'nouveau')
                    <div class="container">
                        <div class="row">

                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <label>
                                <input type="radio" name="role" value="admin" selected checked class="card-input-element" />
                                  <div class="card card-default card-input">
                                    <div class="card-header" style="font-weight: bold">Administrateur</div>
                                    <div class="card-body">
                                        <img style="width: 100%;" src="{{ asset('assets/images/admin.JPG') }}" alt="Administrateur" />
                                    </div>
                                  </div>
                              </label>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-4">
                                <label>
                                  <input type="radio" name="role" value="personnel" class="card-input-element" />
                                    <div class="card card-default card-input">
                                      <div class="card-header" style="font-weight: bold">Personnel</div>
                                      <div class="card-body">
                                          <img style="width: 100%;" src="{{ asset('assets/images/personnel.JPG') }}" alt="Personnel" />
                                      </div>
                                    </div>
                                </label>
                              </div>
                              <div class="col-md-4 col-lg-4 col-sm-4">
                                <label>
                                  <input type="radio" name="role" value="superviseur" class="card-input-element" />
                                    <div class="card card-default card-input">
                                      <div class="card-header" style="font-weight: bold">Superviseur</div>
                                      <div class="card-body">
                                          <img style="width: 100%;" src="{{ asset('assets/images/superviseur.JPG') }}" alt="Superviseur" />
                                      </div>
                                    </div>
                                </label>
                              </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4 col-lg-4 col-sm-4">
                              <label>
                                <input type="radio" name="role" value="livreur" class="card-input-element" />
                                  <div class="card card-default card-input">
                                    <div class="card-header" style="font-weight: bold">Livreur</div>
                                    <div class="card-body">
                                        <img style="width: 100%;" src="{{ asset('assets/images/livreur.JPG') }}" alt="Livreur" />
                                    </div>
                                  </div>
                              </label>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-4">

                              <label>
                                <input type="radio" name="role" value="client" class="card-input-element" />

                                  <div class="card card-default card-input">
                                    <div class="card-header" style="font-weight: bold">Ramassage/Livraison</div>
                                    <div class="card-body">
                                        <img style="width: 100%;" src="{{ asset('assets/images/ramassage.JPG') }}" alt="ramassage" />
                                    </div>
                                  </div>
                              </label>

                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-4">

                              <label>
                                <input type="radio" name="role" value="stock" class="card-input-element" />

                                  <div class="card card-default card-input">
                                    <div class="card-header" style="font-weight: bold">Stockage/Livraison</div>
                                    <div class="card-body">
                                        <img style="width: 100%;" src="{{ asset('assets/images/stock.JPG') }}" alt="stock" />
                                    </div>
                                  </div>
                              </label>

                            </div>
                        </div>

                    </div>
                    @else
                    <div>
                        <div style="display: flex; flex-direction: column; align-items: flex-start;">
                            <div class="container-image-profil">
                                <h1 style="text-align: center">Modifier l'utilisateur: {{ $user->name}} </h1>
                                <h5 style="text-align: center">Ville : {{ $user->ville}} </h5>
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload"><i class="fas fa-edit"></i></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview" style="background-image: url('{{$user->image}}');">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Nom & Prénom: </label>

                            <div class="col-md-9">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required  autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if (in_array("ecom",$user->roles()->get()->pluck('name')->toArray()) || in_array("client",$user->roles()->get()->pluck('name')->toArray()))
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right">Nom du store</label>
                                <div class="col-md-9">
                                    <input name="storeName" type="text" value="{{$user->storeName}}" class="form-control form-control-line" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="prix" class="col-md-3 col-form-label text-md-right">{{ __('Prix de livraison fixé') }}</label>
                                <div class="col-md-9">
                                    <input id="prix" type="number" class="form-control" name="prix" value="{{$user->prix}}">
                                </div>

                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">N° CIN</label>
                            <div class="col-md-9">
                                <input name="cin" type="text" value="{{$user->cin}}" class="form-control form-control-line" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Email: </label>

                            <div class="col-md-9">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" >
                            <label class="col-md-3 col-form-label text-md-right">Ville</label>
                            <div class="col-md-9">
                                <select name="ville" class="form-control form-control-line">
                                <option checked value="{{$user->ville}}">{{$user->ville}}</option>
                                    @foreach ($villes as $ville)
                                    <option value="{{$ville->name}}" class="rounded-circle">
                                        {{$ville->name}}
                                    </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        {{-- <div class="form-roles-container">
                            @switch($user->roles()->get()->pluck('name')->toArray()[0])
                                @case('ramassage')
                                    <input type="hidden" name="roles[]" value="2" id="ramassage" checked>
                                    @include('auth.partiels._ramassage', ['isUpdate' => true])
                                    @break
                            @endswitch
                        </div> --}}

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">Téléphone</label>
                            <div class="col-md-9">
                                <input name="telephone" type="text" value="{{$user->telephone}}"class="form-control form-control-line">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">N° Registre de Commerce</label>
                            <div class="col-md-9">
                                <input name="description" type="text" value="{{$user->description}}"class="form-control form-control-line">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right">RIB</label>
                            <div class="col-md-9">
                                <input name="rib" type="text" value="{{$user->rib}}"class="form-control form-control-line">
                            </div>
                        </div>
                        @if (in_array("client",$user->roles()->get()->pluck('name')->toArray()))
                            <div class="form-group row">
                                <label for="adresse" class="col-md-3 col-form-label text-md-right">{{ __('Adresse de ramassage 1') }}</label>

                                <div class="col-md-9">
                                    <textarea name="adresse" id="adresse" cols="100" rows="5" required>{{$user->adresse}}</textarea >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="adresse2" class="col-md-3 col-form-label text-md-right">{{ __('Adresse de ramassage 2') }}</label>

                                <div class="col-md-9">
                                    <textarea name="adresse2" id="adresse2" cols="100" rows="5" required>{{$user->adresse2}}</textarea >
                                </div>
                            </div>
                        @endif
                    </div>

                    @endif
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </form>


                </div>
            </div>
        </div>
    </div>

</div>


@endsection



@section('javascript')

<script>
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
    $("#imageUpload").change(function() {
        readURL(this);
    });
</script>

    @if ($errors->any())
        <script type="text/javascript">
            $(window).on('load',function(){
                $('#modalSubscriptionForm').modal('show');
            });
        </script>
    @endif
@endsection
