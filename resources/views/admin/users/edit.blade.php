
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
    </style>
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
                <div class="card-header">Modifier l'utilisateur: {{ $user->name}}</div>

                <div class="card-body">
                <form method="POST" action="{{route('admin.users.update',$user)}}">
                    @csrf
                    @method("PUT")
                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">Nom & Prénom: </label>

                        <div class="col-md-10">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required  autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">Nom du store</label>
                        <div class="col-md-10">
                            <input name="storeName" type="text" value="{{$user->storeName}}" class="form-control form-control-line" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">N° CIN</label>
                        <div class="col-md-10">
                            <input name="cin" type="text" value="{{$user->cin}}" class="form-control form-control-line" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label text-md-right">Email: </label>

                        <div class="col-md-10">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">Url de l'image</label>
                        <div class="col-md-10">
                            <input name="image" type="text" value="{{$user->image}}"class="form-control form-control-line">
                        </div>
                    </div>

                    @if (in_array("livreur",$user->roles()->get()->pluck('name')->toArray()))
                    <div id="education_fields">

                    </div>

                    @foreach ($userVilles as $index => $userville)
                <div class="form-group row removeclass{{$index.$userville}}">
                        <label class="col-md-2 col-form-label text-md-right">Ville</label>
                        <div class="col-md-8">
                            <select name="ville[]" class="form-control form-control-line"  onchange="myFunction()" required>
                                <option checked value="{{$userville}}"> {{$userville}}</option>
                                @foreach ($villes as $ville)
                                <option value="{{$ville->name}}" class="rounded-circle">
                                    {{$ville->name}}
                                </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="input-group-btn col-md-1">
                            <button class="btn btn-success " type="button"  onclick="education_fields();"> <span class="mdi mdi-library-plus" aria-hidden="true"></span> </button>
                          </div>
                          <div class="input-group-btn col-md-1">
                          <button class="btn btn-danger" type="button" onclick="remove_education_fields('{{$index.$userville}}');">
                                <span class="mdi mdi-close-box" aria-hidden="true"></span>
                            </button>
                        </div>


                    </div>
                    @endforeach

                    <div class="form-group row"  style="display: none">
                        <div  id="test">
                            <label class="col-md-2 col-form-label text-md-right">Ville</label>
                        <div class="col-md-8">
                            <select name="ville[]" class="form-control form-control-line"  onchange="myFunction()">
                                <option checked value="">Ajouter une ville</option>
                                @foreach ($villes as $ville)
                                <option value="{{$ville->name}}" class="rounded-circle">
                                    {{$ville->name}}
                                </option>
                                @endforeach

                            </select>
                        </div>
                        </div>
                        <div class="input-group-btn col-md-1">
                            <button class="btn btn-success " type="button"> <span class="mdi mdi-library-plus" aria-hidden="true"></span> </button>
                          </div>
                        </div>
                    @else
                    <div class="form-group row" >
                            <label class="col-md-2 col-form-label text-md-right">Ville</label>
                        <div class="col-md-10">
                            <select name="ville[]" class="form-control form-control-line"  onchange="myFunction()">
                            <option checked value="{{$user->ville}}">{{$user->ville}}</option>
                                @foreach ($villes as $ville)
                                <option value="{{$ville->name}}" class="rounded-circle">
                                    {{$ville->name}}
                                </option>
                                @endforeach

                            </select>
                        </div>

                        </div>
                    @endif


                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">Téléphone</label>
                        <div class="col-md-10">
                            <input name="telephone" type="text" value="{{$user->telephone}}"class="form-control form-control-line">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">N° Registre de Commerce</label>
                        <div class="col-md-10">
                            <input name="description" type="text" value="{{$user->description}}"class="form-control form-control-line">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">RIB</label>
                        <div class="col-md-10">
                            <input name="rib" type="text" value="{{$user->rib}}"class="form-control form-control-line">
                        </div>
                    </div>



                    <div class="form-group row">
                        <label for="roles" class="col-md-12 col-form-label text-center font-bold font-16">Rôles Rafex : </label>
                        <label for="roles" class="col-md-2 col-form-label text-md-right">Rôle : </label>
                        <div class="col-md-10 d-flex p-t-10 justify-content-around">
                            <div class="form-check">
                                <input type="radio" name="roles[]" value="1" id="admin" @if(implode($user->roles()->get()->pluck('name')->toarray()) == "admin") checked @endif>
                                <label for="admin">Admin</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="roles[]" value="3" id="Livreur" @if(implode($user->roles()->get()->pluck('name')->toarray()) == "livreur") checked @endif >
                                <label for="Livreur">Livreur</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="roles[]" value="4" id="Personnel" @if(implode($user->roles()->get()->pluck('name')->toarray()) == "personnel") checked @endif>
                                <label for="Personnel">Personnel</label>
                            </div>
                        </div>
                        <label for="roles" class="col-md-12 col-form-label text-center font-bold font-16">Utilisateur Client : </label>
                        <label for="roles" class="col-md-2 col-form-label text-md-right">Service : </label>
                        <div class="col-md-10 d-flex p-t-10 justify-content-around">
                            <div class="form-check">
                                <input type="radio" name="roles[]" value="6" id="nv" @if(implode($user->roles()->get()->pluck('name')->toarray()) == "nouveau") checked @endif>
                                <label for="nv">Nouveau</label>
                            </div>
                        <div class="form-check">
                            <input type="radio" name="roles[]" value="2" id="cl" @if(implode($user->roles()->get()->pluck('name')->toarray()) == "client") checked @endif>
                            <label for="cl">Collecte, Livraison</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="roles[]" value="5" id="cls" @if(implode($user->roles()->get()->pluck('name')->toarray()) == "ecom") checked @endif>
                            <label for="cls">Collecte, Stockage, Livraison</label>
                        </div>


                        </div>


                            <label for="type" class="col-md-2 col-form-label text-md-right">Statut : </label>
                            <div class="col-md-10 d-flex p-t-10 justify-content-around">
                            <div class="form-check">
                                <input type="radio" name="statut" value="0" id="Premium" @if( !$user->statut) checked @endif>
                                <label for="Premium">Premium</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" name="statut" value="1" id="VIP" @if($user->statut) checked @endif>
                                <label for="VIP">VIP</label>
                            </div>

                            </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-md-right">Prix de livraison</label>
                        <div class="col-md-10">
                            <input name="prix" type="number" value="{{$user->prix}}"class="form-control form-control-line">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </form>


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
        var room = 1;
        function education_fields() {

            room++;
            var objTo = document.getElementById('education_fields')
            var divtest = document.createElement("div");
            divtest.setAttribute("class", "row mb-2 removeclass"+room);
            var rdiv = 'removeclass'+room;

            divtest.innerHTML  = $("#test").html() + '<div class="input-group-btn col-md-1"> <button class="btn btn-danger" type="button" onclick="remove_education_fields('+ room +');"> <span class="mdi mdi-close-box" aria-hidden="true"></span> </button></div></div></div></div><div class="clear"></div>';

            objTo.appendChild(divtest)
        }
        function remove_education_fields(rid) {
            $('.removeclass'+rid).remove();
        }

    </script>
@endsection
