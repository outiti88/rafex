@extends('racine')

@section('title')
{{$user->name}} | Rafex
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Profile Page</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Rafex</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                <center class="m-t-30">
                        <img src="{{$user->image}}" id="imagePreview" class="rounded-circle" width="150" />
                        <h4 class="card-title m-t-10">{{$user->name}}</h4>
                        <h6 class="card-subtitle">ICE: {{$user->description}}</h6>
                        <div class="row text-center justify-content-md-center">
                        <div class="col-4"><a href="{{route('commandes.index')}}" class="link"><i class="icon-people"></i> <font class="font-medium">{{$total}} <br>Commandes</font></a></div>
                            <div class="col-4"><a href="{{route('facture.index')}}" class="link"><i class="icon-picture"></i> <font class="font-medium">{{$facture}} <br>Factures</font></a></div>
                        </div>
                    </center>
                </div>
                <div>
                    <hr> </div>
                <div class="card-body"> <small class="text-muted">Addresse email </small>
                    <h6>{{$user->email}}</h6> <small class="text-muted p-t-30 db">Téléphone</small>
                    <h6>{{$user->telephone}}</h6> <small class="text-muted p-t-30 db">Addresse</small>
                    <h6>{{$user->adresse}}</h6>
                    <div class="map-box">
                        <iframe src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=Marjane,%20Tanger%20PAC,%20Avenue%20des%20Forces%20Arm%C3%A9es%20Royales,%20Tanger%2090060+(Decathlon%20Tanger)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div> <small class="text-muted p-t-30 db">Reseaux Sociaux</small>
                    <br/>
                    <a href="https://www.facebook.com/DeliveryRafex" target="_blank" class="btn btn-circle btn-secondary"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/DeliveryRafex" target="_blank" class="btn btn-circle btn-secondary"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/company/Rafex/" target="_blank" class="btn btn-circle btn-secondary"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal form-material" method="POST" action="{{route('profil.update',$user)}}"  enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="form-group">
                            <label for="name" class="col-md-12">Nom & Prénom: </label>

                        <div class="col-md-12">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required  autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-12">Email: </label>

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">

                            <label class="custom-file-labe col-md-12" for="imageUpload">Choisir une photo</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="imageUpload" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="imageUpload">Télécharger</label>
                              </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Téléphone</label>
                            <div class="col-md-12">
                            <input name="telephone" type="text" value="{{$user->telephone}}" class="form-control form-control-line">
                            </div>
                        </div>
                        @can('client')
                            <div class="form-group">
                                <label class="col-md-12">Adresse de ramassage 1</label>
                                <div class="col-md-12">
                                <textarea name="adresse" rows="5" class="form-control form-control-line">{{$user->adresse}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Adresse de ramassage 2</label>
                                <div class="col-md-12">
                                <textarea name="adresse2" rows="5" class="form-control form-control-line">{{$user->adresse2}}</textarea>
                                </div>
                            </div>
                        @endcan
                        @cannot('client')
                            <div class="form-group">
                                <label class="col-md-12">Adresse</label>
                                <div class="col-md-12">
                                <textarea name="adresse" rows="5" class="form-control form-control-line">{{$user->adresse}}</textarea>
                                </div>
                            </div>
                        @endcannot

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success">Modifier</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>

</div>
@endsection
@section('javascript')
<script>
  function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#imageUpload").change(function() {
    readURL(this);
});
</script>
@endsection
