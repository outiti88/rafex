<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rafex | Tracking</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.5.2/lux/bootstrap.min.css" integrity="sha384-9+PGKSqjRdkeAU7Eu4nkJU8RFaH8ace8HGXnkiKMP9I9Te0GJ4/km3L1Z8tXigpG" crossorigin="anonymous">
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('/assets/images/favicon.png')}}">

    <style>
        body {
            color: #000;
            overflow-x: hidden;
            height: 100%;
            background-color: #007db2;
            background-repeat: no-repeat
        }

        .card {
            z-index: 0;
            background-color: #ECEFF1;
            padding-bottom: 20px;
            margin-top: 90px;
            margin-bottom: 90px;
            border-radius: 10px
            display: flex;
    justify-content: center;
    text-align: center;
    padding: 10px;
        }

        .top {
            padding-top: 40px;
            padding-left: 13% !important;
            padding-right: 13% !important
        }

        #progressbar {
            margin-bottom: 30px;
            overflow: hidden;
            color: #455A64;
            padding-left: 0px;
            margin-top: 30px
        }

        #progressbar li {
            list-style-type: none;
            font-size: 13px;
            width: 25%;
            float: left;
            position: relative;
            font-weight: 400
        }

        .icon-content i {
            font-size: 2em;
            padding-right: 10px;
            color: #007db2;
        }

        #progressbar li i {
            width: 40px;
            height: 40px;
            line-height: 45px;
            display: block;
            font-size: 20px;
            background: #dd885e;
            color:white;
            border-radius: 50%;
            margin: auto;
            padding: 0px
        }

    p{
        font-size: 0.8em;
    color: #007db2;
    }


        #progressbar li.active:after {
            content: '';
            width: 100%;
            height: 12px;
            background: #f16821;
            position: absolute;
            left: 0;
            top: 16px;
            z-index: -1
        }

        .step0:after {
            content: '';
            width: 100%;
            height: 12px;
            background: #dd885e;
            position: absolute;
            left: 0;
            top: 16px;
            z-index: -1
        }

        #progressbar li:last-child:after {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            position: absolute;
            left: -50%
        }

        #progressbar li:nth-child(2):after,
        #progressbar li:nth-child(3):after {
            left: -50%
        }

        #progressbar li:first-child:after {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            position: absolute;
            left: 50%
        }

        #progressbar li:last-child:after {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px
        }

        #progressbar li:first-child:after {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px
        }

        #progressbar li.active i,
        #progressbar li.active i {
            background: #f16821;
            border-color: white;
            border-style: solid;
            color : white;
        }



        .icon {
            width: 40px;
            height: 40px;
            margin-right: 15px
        }

        .icon-content {
            padding-bottom: 20px
        }


    </style>
</head>
<body>
    <div class="container px-1 px-md-4 py-5 mx-auto">

        @if(session()->has('notfound'))
        <div class="alert alert-dismissible alert-danger col-12">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oops ! </strong>La commande numéro {{session()->get('notfound')}} est introuvable !
          </div>
        @endif

        <div class="card">
            <div style="display: flex;
            justify-content: center;
            align-items: center;  margin: 30px;">
                    <a alt="Rafex" href="https://Rafex.ma">
                        <img src="{{asset('assets/images/logo-light-text.png')}}" style="
                    WIDTH: 20%;
                    "class="light-logo" alt="homepage" />
                    </a>

            </div>

            <form method="GET" action="{{route('tracking.index')}}">
                @csrf
                <fieldset>
                  <legend style="margin: 30px; text-transform: uppercase;">Tapez le numéro de votre commande</legend>
                  <div class="form-group row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Numéro: </label>
                    <div class="col-sm-10">
                      <input type="numero" name="numero" class="form-control-plaintext" id="numero" placeholder="Exemple: COL12598551">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-success" style="background-color: #f16821;
                  border-color: #f16821">Rechercher</button>
                </fieldset>
              </form>
        </div>
    </div>


</body>
</html>
