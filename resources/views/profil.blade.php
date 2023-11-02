@extends('racine')

@section('title')
{{$user->name}} | Cavallo
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Profile Page</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Cavallo</a></li>
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
                <center class="m-t-30"> <img src="{{$user->image}}" class="rounded-circle" width="150" />
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
                    <a href="https://www.facebook.com/DeliveryCavallo" target="_blank" class="btn btn-circle btn-secondary"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/DeliveryCavallo" target="_blank" class="btn btn-circle btn-secondary"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/company/Cavallo/" target="_blank" class="btn btn-circle btn-secondary"><i class="fab fa-linkedin"></i></a>
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

                            <label class="custom-file-labe col-md-12" for="inputGroupFile01">Choisir une photo</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputGroupFile01">Télécharger</label>
                              </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Téléphone</label>
                            <div class="col-md-12">
                            <input name="telephone" type="text" value="{{$user->telephone}}" class="form-control form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Adresse</label>
                            <div class="col-md-12">
                            <textarea name="adresse" rows="5" class="form-control form-control-line">{{$user->adresse}}</textarea>
                            </div>
                        </div>

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
    var arr =
        {"Martil" : "45,00 DH" ,
        "M'diq" : "45,00 DH" ,
        "Fnideq" : "45,00 DH" ,
        "larache" : "45,00 DH" ,
        "Ksar lkbir" : "50,00 DH" ,
        "Ksar sghir" : "50,00 DH" ,
        "Assilah" : "50,00 DH" ,
        "Al Hoceima" : "50,00 DH" ,
        "Bab bered" : "50,00 DH" ,
        "Bab taza" : "50,00 DH" ,
        "Dardara" : "50,00 DH" ,
        "Akchour" : "50,00 DH" ,
        "Tetouan" : "40,00 DH" ,
        "Taza" : "40,00 DH" ,
        "Casablanca" : "30,00 DH" ,
        "Marrakech" : "40,00 DH" ,
        "Fès" : "35,00 DH" ,
        "Tiznit" : "45,00 DH" ,
        "Taroudant" : "45,00 DH" ,
        "Haouara" : "45,00 DH" ,
        "Belfaa" : "45,00 DH" ,
        "Massa" : "45,00 DH" ,
        "Khmis Ait Amira" : "45,00 DH" ,
        "Sidi Bibi" : "45,00 DH" ,
        "Bikra" : "45,00 DH" ,
        "Oulad Tayma" : "45,00 DH" ,
        "El-Hajeb" : "40,00 DH" ,
        "Boufakrane" : "45,00 DH" ,
        "Sabaa Aiyoun" : "45,00 DH",
        "Azrou" : "45,00 DH" ,
        "El Hadj Kaddour" : "45,00 DH" ,
        "Ifrane" : " 40,00 DH" ,
        "Imouzar" : " 45,00 DH" ,
        "Ain Leuh" : " 45,00 DH" ,
        "Moulay Idriss Zerhoun" : " 45,00 DH" ,
        "Ain karma" : " 45,00 DH" ,
        "Ain Taoujdate" : " 45,00 DH" ,
        "EL mhaya" : " 45,00 DH" ,
        "Sidi Addi" : " 45,00 DH" ,
        "Tiflet" : "45,00 DH " ,
        "Tarfaya" : " 45,00 DH" ,
        "Boujdour" : "45,00 DH " ,
        "Es -semara" : "45,00 DH " ,
        "Dakhla" : "45,00 DH " ,
        "Sidi  Yahya El GHarb" : "45,00 DH " ,
        "Sidi  Yahya des zaer" : "45,00 DH " ,
        "Temara" : "30,00 DH " ,
        "Sale" : "30,00 DH " ,
        "Ain El Aouda" : " 30,00 DH" ,
        "Sale EL-jadida" : "30,00 DH " ,
        "Ain Atiq" : "45,00 DH" ,
        "Tamsna" : "45,00 DH" ,
        "Mers El kheir" : "45,00 DH" ,
        "Bouknadal" : "45,00 DH" ,
        "Skhirat" : "45,00 DH" ,
        "Sidi Taibi" : "45,00 DH" ,
        "Kenitra" : "40,00 DH" ,
        "Sidi slimane" : "45,00 DH" ,
        "Sidi kacem" : "45,00 DH" ,
        "Berkan" : " 50,00 DH" ,
        "Aklim" : "50,00 DH " ,
        "Cafemor-chouihia" : " 50,00 DH" ,
        "Saidia" : "50,00 DH " ,
        "Ahfir" : "50,00 DH " ,
        "Taourirt" : " 50,00 DH" ,
        "Beni drar" : "50,00 DH" ,
        "Bni Oukil" : "50,00 DH" ,
        "Jerada" : "50,00 DH" ,
        "Laayoune" : "50,00 DH" ,
        "Ejdar" : "50,00 DH" ,
        "Ihddaden" : "50,00 DH" ,
        "Touima" : "50,00 DH" ,
        "bouraj" : "50,00 DH" ,
        "Bni Ensar" : "50,00 DH" ,
        "Farkhana" : "50,00 DH" ,
        "Beni chiker" : "50,00 DH" ,
        "bouaarek" : "50,00 DH" ,
        "selouane" : "50,00 DH" ,
        "zghanghan" : "50,00 DH" ,
        "Arekmane" : "50,00 DH" ,
        "El Aroui" : "50,00 DH" ,
        "Safi" : "40,00 DH" ,
        "Essaouira" : "40,00 DH" ,
        "Chichaoua" : "45,00 DH" ,
        "Tamnsourt" : "45,00 DH" ,
        "Oudaya (Marrakech)" : "45,00 DH" ,
        "Douar Lahna" : "50,00 DH" ,
        "Douar  Sidi Moussa" : "45,00 DH" ,
        "Belaaguid" : "45,00 DH" ,
        "Dar Tounssi" : "45,00 DH" ,
        "Chouiter" : "45,00 DH" ,
        "Sidi Zouine" : "45,00 DH" ,
        "Tamesloht" : "45,00 DH" ,
        "Ait Ourir" : "45,00 DH" ,
        "Tahannaout" : "45,00 DH" ,
        "Tit Melil" : "45,00 DH" ,
        "Mediouna" : "45,00 DH" ,
        "Bouskoura" : "45,00 DH" ,
        "Mohammedia" : "40,00 DH" ,
        "Echellalat" : "50,00 DH" ,
        "Deroua" : "50,00 DH" ,
        "Nouacer" : "50,00 DH" ,
        "Dar bouaaza" : "50,00 DH" ,
        "Tamaris" : "50,00 DH" ,
        "Al rahma" : "45,00 DH" ,
        "Ouad zem" : "50,00 DH" ,
        "Boujniba" : "50,00 DH" ,
        "Boulnouar" : "50,00 DH" ,
        "Fini" : "50,00 DH" ,
        "Beni Mellal" : "40,00 DH" ,
        "Ouarzazate" : "50,00 DH" ,
        "Tghsaline" : "45,00 DH" ,
        "Ait Ishaq" : "45,00 DH" ,
        "Lakbab" : "45,00 DH" ,
        "lahri" : "45,00 DH" ,
        "Mrirt" : "45,00 DH" ,
        "Settat" : "40,00 DH" ,
        "sidi bouzid" : "40,00 DH" ,
        "Moulay abedellah" : "45,00 DH" ,
        "sidi aabed (eljadida)" : "45,00 DH" ,
        "azmour" : "45,00 DH" ,
        "Bir jdid" : "45,00 DH" ,
        "Msewar rassou" : "50,00 DH" ,
        "Sidi Ismail" : "50,00 DH" ,
        "Sidi Benour" : "50,00 DH" ,
        "Khmis Zmamra" : "50,00 DH" ,
        "Oulad  frej" : "50,00 DH" ,
        "Chefchaouen" : "40,00 DH" ,
        "Elbradiya" : "45,00 DH" ,
        "Ihrem Laalam" : "45,00 DH" ,
        "Elfkih ben saleh" : "45,00 DH" ,
        "Souk essabt/ oulad nema" : "45,00 DH" ,
        "Azilal" : "50,00DH" ,
        "Ouaouizght" : "45,00DH" ,
        "Khnifra" : "45,00 DH" ,
        "Ben louidane" : "50,00 DH" ,
        "Zauiyat Echikh" : "50,00 DH" ,
        "Tadla" : "50,00 DH" ,
        "Afourar" : "50,00 DH" ,
        "Laayata" : "50,00 DH" ,
        "Oulad  Youssef" : "50,00 DH" ,
        "Adouz" : "50,00 DH" ,
        "Sidi  Jaber" : "50,00 DH" ,
        "Ooulad  Zidouh" : "50,00 DH" ,
        "Lakssiba" : "50,00 DH" ,
        "Beni  Aayat" : "50,00 DH" ,
        "Oulad  Aayad" : "50,00 DH" ,
        "Tagzirt" : "50,00 DH" ,
        "Sidi Issa" : "50,00 DH" ,
        "Oulad M'barek" : "50,00 DH" ,
        "Oulad  Zam" : "50,00 DH" ,
        "Agadir" : "40,00 DH" ,
        "Oulad Ayach" : "50,00 DH" ,
        "Foum Nsser" : "50,00 DH ",
        "Tanougha" : "50,00 DH ",
        "Taounate" : "50,00 DH ",
        "Tahla" : " 45,00DH",
        "Guercif" : " 45,00DH",
        "Ouad  Amlil" : " 45,00DH",
        "Essaouira" : " 40,00 DH",
        "Berchid" : " 40,00 DH",
        "Bouznika" : " 40,00 DH",
        "Tanger" : " 40,00 DH",
        "Guelmim" : " 50.00 DH",
        "Tan Tan" : " 50.00 DH",
        "Tan Tan plage" : " 50.00 DH",
        "Sidi ifni" : " 50.00 DH",
        "Ait lmour" : " 45.00DH",
        "Souk Larbaa" : " 45.00DH",
        "Rabat" : "25.00 DH ",
        "Driouach" : "60,00 DH ",
        "Errachidia" : " 50,00 DH",
        "Khmiset" : " 50,00 DH",
        "Ben Slimane" : " 50,00 DH",
        "Ouazzane" : "45,00 DH ",
        "Sefrou" : " 45,00 DH",
        "Sidi benour" : " 50,00 DH",
        "Had Hrara" : " 60,00 DH",
        "Zrarda" : "45,00 DH ",
        "Zoumi" : "50 ,00 DH ",
        "Mokrissat" : "50 ,00 DH ",
        "Souk ELaha" : "50 ,00 DH ",
        "Ain Dorij" : "50 ,00 DH ",
        "Sidi redouane" : "50 ,00 DH ",
        "Massmouda" : " 50 ,00 DH",
        "Sidi Rahal" : " 45 ,00 DH",
        "Had Soualem" : " 45 ,00 DH",
        "Oujda" : "40 ,00 DH ",
        "Sefrou" : " 45 ,00 DH",
        "Oulad tayeb" : "40,00  DH ",
        "Ain chekaf" : "40,00  DH ",
        "Ain chegag" : " 50,00 DH",
        "Bel ksir" : " 50,00 DH",
        "Meknès" : "40,00  DH ",
        "Sidi Hrazem" : "50,00 DH ",
        "Nador" : "40,00  DH ",
        "Khouribga" : "40,00 DH",
         "El Youssoufia" : "50,00 DH",
         "kelaat sraghna": "50,00 DH"

        };





  var x3 = document.getElementById("ville");


  for (const [key, value] of Object.entries(arr)) {
  var option = document.createElement("option");
         x3.appendChild(option);
        var text1 = document.createTextNode(key);
        option.appendChild(text1);


	}
</script>
@endsection
