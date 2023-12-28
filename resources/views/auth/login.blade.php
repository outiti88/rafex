
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png"  href="{{url('/css/inscription/assets/img/apple-icon.png')}}">
  <link rel="icon" type="image/png"  href="{{url('/css/inscription/assets/img/favicon.png')}}">
  <title>
    Rafex - Se Connecter
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{url('/css/inscription/assets/css/nucleo-icons.css')}}" rel="stylesheet" />
  <link href="{{url('/css/inscription/assets/css/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle"  href="{{url('/css/inscription/assets/css/material-dashboard.css?v=3.0.0s')}}" rel="stylesheet" />

</head>

<body class="bg-gray-200">

  <main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('{{url('/css/inscription/assets/img/bg-signin-2.jpg')}}');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Connectez à votre espace</h4>

                </div>
              </div>
              <div class="card-body">
                <form role="form" class="text-start"  method="POST" action="{{ route('login') }}">
                    @csrf
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Adresse E-mail</label>
                    <input type="email"  name="email" class="form-control  @error('email') is-invalid @enderror" value="{{ old('email') }}" required  autofocus  autocomplete="off">
                  </div>
                  @error('email')
                  <span class="invalid-feedback" style="display: block" role="alert" style="color:red">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input id="showPasswordId"  type="password" class="form-control  @error('password') is-invalid @enderror" type="password" name="password"  required autocomplete="off">
                  </div>
                  @error('password')
                  <span class="invalid-feedback" style="display: block" role="alert" style="color:red">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                  <div class="form-check form-switch d-flex align-items-center mb-3">
                    <input class="form-check-input" onclick="showPassword()" type="checkbox" id="rememberMe">
                    <label class="form-check-label mb-0 ms-2" for="rememberMe">Afficher Mot de Passe</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Se Connecter</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    Non inscrit?
                    <a href="{{ route('user.nouveau') }}" class="text-primary text-gradient font-weight-bold">Rejoignez nous</a>
                  </p>
                  <p class="mt-4 text-sm text-center">
                    <a href="{{ route('password.request')}}" class="text-primary text-gradient font-weight-bold">
                        Mot de passe
                    </a>
                        Oublié?
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">

            <div class="col-12 col-md-6" style="float: left; text-align: left;">
              <div class="text-center text-sm text-white text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart" aria-hidden="true"></i> by
                <a href="https://idesignsolution.com/" class="font-weight-bold text-white" target="_blank">iDesign Business</a>
              </div>
            </div>

          <div class="col-12 col-md-6" style="float: right; text-align: right;">
            <a href="https://www.Rafex.ma/" target="_blank">
              <img src="{{url('/css/inscription/assets/img/logo-footer.png')}}" alt="logo-footer.png">
            </a>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="{{url('/css/inscription/assets/js/core/popper.min.js')}}"></script>
  <script src="{{url('/css/inscription/assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{url('/css/inscription/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{url('/css/inscription/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{url('/css/inscription/assets/js/material-dashboard.min.js?v=3.0.0')}}"></script>
  <script>
    function showPassword() {
    var x = document.getElementById("showPasswordId");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
    }
</script>
</body>

</html>
