<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png"  href="{{url('/css/inscription/assets/img/apple-icon.png')}}">
  <link rel="icon" type="image/png"  href="{{url('/css/inscription/assets/img/favicon.png')}}">
  <title>
    Cavallo - S'inscrire pour un meilleur service de livraison
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
  <link rel="stylesheet" type="text/css">

  <link id="pagestyle"  href="{{url('/css/inscription/assets/css/material-dashboard.css?v=3.0.0s')}}" rel="stylesheet" />
</head>

<body class="">
    <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('{{url('/css/inscription/assets/img/illustrations/illustration-signup-cavallo.jpg')}}'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Remplissez le formulaire</h4>
                  <p class="mb-0">Nous allons vous contacter pour finaliser l'inscription.</p>
                </div>
                <div class="card-body">
                  <form role="form validate-form" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="input-group input-group-outline mb-3 @if ($errors->any()) is-filled @endif  @error('name') is-invalid @enderror">
                      <label class="form-label">Nom Complet</label>
                      <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required  autofocus>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="input-group input-group-outline mb-3 @if ($errors->any()) is-filled @endif ">
                      <label class="form-label">Nom du Store</label>
                      <input id="storeName" type="text" class="form-control" name="storeName" value="{{ old('storeName') }}"  required  >
                    </div>
                    <div class="input-group input-group-outline mb-3 @if ($errors->any()) is-filled @endif ">
                      <label class="form-label">Téléphone</label>
                      <input id="telephone" type="phone" class="form-control" name="telephone" value="{{ old('telephone') }}"  required  >
                    </div>
                    <div class="input-group input-group-outline mb-3 @if ($errors->any()) is-filled @endif ">
                      <label class="form-label">N°: CIN</label>
                      <input id="cin" type="text" class="form-control" name="cin" value="{{ old('cin') }}"  required  >
                    </div>
                    <div class="input-group input-group-outline mb-3 @if ($errors->any()) is-filled @endif @error('email') is-invalid @enderror">
                        <label class="form-label">Adresse E-mail</label>
                        <input id="email" type="email" class="form-control " name="email" value="{{ old('email') }}" required >

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="input-group input-group-outline mb-3 @if ($errors->any()) is-filled @endif @error('password') is-invalid @enderror">
                      <label class="form-label">Mot de passe</label>
                      <input id="password" class="form-control " type="password"  name="password" required >
                         @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input-group input-group-outline mb-3 @if ($errors->any()) is-filled @endif @error('password') is-invalid @enderror">
                        <label class="form-label">Confirmez votre Mot de passe</label>
						<input id="password-confirm" class="form-control" type="password"  name="password_confirmation" required>
                      </div>
                    <div class="form-check form-check-info text-start ps-0">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                      <label class="form-check-label" for="flexCheckDefault">
                        J'accepte les <a href="javascript:;" class="text-dark font-weight-bolder">Termes & Conditions</a>
                      </label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Inscrire</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Déjà Inscrit?
                    <a href="/login" class="text-primary text-gradient font-weight-bold">Se Connecter</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
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
</body>

</html>
