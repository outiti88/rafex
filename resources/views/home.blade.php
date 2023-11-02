
<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Cavallo Delivery Tanger propose
    un service de collecte, de stockage, d’emballage et d’expédition de vos produits aux clients.">
    <meta name="author" content="Outiti Ayoub">
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('/assets/images/favicon.png')}}">
    <title>Bienvenue</title>
    <!-- Custom CSS -->
    <link href="{{ url('/sass/style.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="error-box">
            <div class="error-body text-center">
                <h1 class="error-title" style="color: #467a0f;font-size: 4em;">BIENVENUE</h1>
                <h3 class="text-uppercase error-subtitle">{{Auth::user()->name}}</h3>
                <p class="text-muted m-t-30 m-b-30">Bienvenue sur notre plateforme Cavallo Tracking</p>
                <p class="text-muted m-t-30 m-b-30">Votre Compte sera activé après 24H</p>
                <p class="text-muted m-t-30 m-b-30">Pour plus d'informations <br> contactez-nous sur : <a href="tel:+212649517070">+212 649-517070</a> </p>

                <a class="btn btn-danger btn-rounded waves-effect waves-light m-b-40"  href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off m-r-5 m-l-5"></i> Deconnexion</a>
                         <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
        </div>

    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../../assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="../../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    </script>
</body>

</html>
