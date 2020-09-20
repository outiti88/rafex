@extends('layouts.app')



@section('style')
    <style>
            
            .container-login100 {
                width: 100%;
                min-height: 100vh;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                background: #00000000;
            }

            a {
                font-size: 14px;
                line-height: 1.7;
                color: #666666;
                margin: 0px;
                transition: all 0.4s;
                -webkit-transition: all 0.4s;
                -o-transition: all 0.4s;
                -moz-transition: all 0.4s;
            }

            a:focus {
                outline: none !important;
            }

            a:hover {
                text-decoration: none;
                color: #e85f03;
            }


            /*---------------------------------------------*/

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                margin: 0px;
            }

            p {
                font-size: 14px;
                line-height: 1.7;
                color: #666666;
                margin: 0px;
            }

            ul,
            li {
                margin: 0px;
                list-style-type: none;
            }


            /*---------------------------------------------*/

            input {
                outline: none;
                border: none;
            }

            textarea {
                outline: none;
                border: none;
            }

            textarea:focus,
            input:focus {
                border-color: transparent !important;
            }

            input:focus::-webkit-input-placeholder {
                color: transparent;
            }

            input:focus:-moz-placeholder {
                color: transparent;
            }

            input:focus::-moz-placeholder {
                color: transparent;
            }

            input:focus:-ms-input-placeholder {
                color: transparent;
            }

            textarea:focus::-webkit-input-placeholder {
                color: transparent;
            }

            textarea:focus:-moz-placeholder {
                color: transparent;
            }

            textarea:focus::-moz-placeholder {
                color: transparent;
            }

            textarea:focus:-ms-input-placeholder {
                color: transparent;
            }

            input::-webkit-input-placeholder {
                color: #999999;
            }

            input:-moz-placeholder {
                color: #999999;
            }

            input::-moz-placeholder {
                color: #999999;
            }

            input:-ms-input-placeholder {
                color: #999999;
            }

            textarea::-webkit-input-placeholder {
                color: #999999;
            }

            textarea:-moz-placeholder {
                color: #999999;
            }

            textarea::-moz-placeholder {
                color: #999999;
            }

            textarea:-ms-input-placeholder {
                color: #999999;
            }


            /*---------------------------------------------*/

            button {
                outline: none !important;
                border: none;
                background: transparent;
            }

            button:hover {
                cursor: pointer;
            }

            iframe {
                border: none !important;
            }


            /*//////////////////////////////////////////////////////////////////
            [ Utility ]*/

            .txt1 {
                font-size: 13px;
                line-height: 1.5;
                color: #999999;
            }

            .txt2 {
                font-size: 13px;
                line-height: 1.5;
                color: #666666;
            }


            /*//////////////////////////////////////////////////////////////////
            [ login ]*/

            .limiter {
                width: 100%;
                margin: 0 auto;
            }

            .wrap-login100 {
                width: 70%;
                background: white;
                border-radius: 10px;
                overflow: hidden;
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                padding: 97px 130px 80px 95px;
                margin: 20px;
                border-color: #e6e6e6;
                border-style: solid;
            }


            /*------------------------------------------------------------------
            [  ]*/

            .login100-pic {
                width: 316px;
            }

            .login100-pic img {
                max-width: 100%;
            }


            /*------------------------------------------------------------------
            [  ]*/

            .login100-form {
                width: 290px;
            }

            .login100-form-title {
                font-size: 2.5em;
                color: #e85f03;
                line-height: 1.2;
                text-align: center;
                width: 100%;
                display: block;
                padding-bottom: 40px;
                font-family: sans-serif;
                font-weight: bold;
            }


            /*---------------------------------------------*/

            .wrap-input100 {
                position: relative;
                width: 100%;
                z-index: 1;
                margin-bottom: 10px;
            }

            .input100 {
                font-size: 15px;
                line-height: 1.5;
                color: #666666;
                display: block;
                width: auto;
                background: #f3e3e3;
                height: 50px;
                border-radius: 25px;
                padding: 0 30px 0 68px;
            }


            /*------------------------------------------------------------------
            [ Focus ]*/

            .focus-input100 {
                display: block;
                position: absolute;
                border-radius: 25px;
                bottom: 0;
                left: 0;
                z-index: -1;
                width: 100%;
                height: 100%;
                box-shadow: 0px 0px 0px 0px;
                color: rgb(232 95 3);
            }

            .input100:focus+.focus-input100 {
                -webkit-animation: anim-shadow 0.5s ease-in-out forwards;
                animation: anim-shadow 0.5s ease-in-out forwards;
            }

            @-webkit-keyframes anim-shadow {
                to {
                    box-shadow: 0px 0px 70px 25px;
                    opacity: 0;
                }
            }

            @keyframes anim-shadow {
                to {
                    box-shadow: 0px 0px 70px 25px;
                    opacity: 0;
                }
            }

            .symbol-input100 {
                font-size: 15px;
                display: -webkit-box;
                display: -webkit-flex;
                display: -moz-box;
                display: -ms-flexbox;
                display: flex;
                align-items: center;
                position: absolute;
                border-radius: 25px;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 100%;
                padding-left: 35px;
                pointer-events: none;
                color: #666666;
                -webkit-transition: all 0.4s;
                -o-transition: all 0.4s;
                -moz-transition: all 0.4s;
                transition: all 0.4s;
            }

            .input100:focus+.focus-input100+.symbol-input100 {
                color: #e85f03;
                padding-left: 28px;
            }


            /*------------------------------------------------------------------
            [ Button ]*/

            .container-login100-form-btn {
                width: 100%;
                display: -webkit-box;
                display: -webkit-flex;
                display: -moz-box;
                display: -ms-flexbox;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .login100-form-btn {
                font-size: 15px;
                line-height: 1.5;
                color: #fff;
                text-transform: uppercase;
                width: 100%;
                height: 50px;
                border-radius: 25px;
                background: #e85f03;
                display: -webkit-box;
                display: -webkit-flex;
                display: -moz-box;
                display: -ms-flexbox;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 0 25px;
                -webkit-transition: all 0.4s;
                -o-transition: all 0.4s;
                -moz-transition: all 0.4s;
                transition: all 0.4s;
            }

            .login100-form-btn:hover {
                background: #fff;
                color: #e85f03;
                border-color: #e85f03 ;
                border-style: solid;
            }


            /*------------------------------------------------------------------
            [ Responsive ]*/

            @media (max-width: 992px) {
                .wrap-login100 {
                    padding: 177px 90px 33px 85px;
                }
                .login100-pic {
                    width: 35%;
                }
                .login100-form {
                    width: 50%;
                }
            }

            @media (max-width: 768px) {
                .wrap-login100 {
                    padding: 100px 80px 33px 80px;
                }
                .login100-pic {
                    display: none;
                }
                .login100-form {
                    width: 100%;
                }
            }

            @media (max-width: 576px) {
                .wrap-login100 {
                    padding: 100px 15px 33px 15px;
                }
            }


            /*------------------------------------------------------------------
            [ Alert validate ]*/

            .validate-input {
                position: relative;
            }

            .alert-validate::before {
                content: attr(data-validate);
                position: absolute;
                max-width: 70%;
                background-color: white;
                border: 1px solid #c80000;
                border-radius: 13px;
                padding: 4px 25px 4px 10px;
                top: 50%;
                -webkit-transform: translateY(-50%);
                -moz-transform: translateY(-50%);
                -ms-transform: translateY(-50%);
                -o-transform: translateY(-50%);
                transform: translateY(-50%);
                right: 8px;
                pointer-events: none;
                color: #c80000;
                font-size: 13px;
                line-height: 1.4;
                text-align: left;
                visibility: hidden;
                opacity: 0;
                -webkit-transition: opacity 0.4s;
                -o-transition: opacity 0.4s;
                -moz-transition: opacity 0.4s;
                transition: opacity 0.4s;
            }

            .alert-validate::after {
                content: "\f06a";
                display: block;
                position: absolute;
                color: #c80000;
                font-size: 15px;
                top: 50%;
                -webkit-transform: translateY(-50%);
                -moz-transform: translateY(-50%);
                -ms-transform: translateY(-50%);
                -o-transform: translateY(-50%);
                transform: translateY(-50%);
                right: 13px;
            }

            .alert-validate:hover:before {
                visibility: visible;
                opacity: 1;
            }

            @media (max-width: 992px) {
                .alert-validate::before {
                    visibility: visible;
                    opacity: 1;
                }
            }

    </style>
@endsection

@section('content')
<div class="container-login100">

    <div class="wrap-login100">
        <div class="login100-pic js-tilt" data-tilt>
            <img src="{{url('/assets/images/favicon.png')}}" alt="IMG">
        </div>
                    <form class="login100-form validate-form"  method="POST" action="{{ route('login') }}">
                        @csrf

                        <span class="login100-form-title">
                            CONNECTEZ VOUS
                        </span>

                        <div class="wrap-input100 validate-input">
                            <input class="input100 form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>
                            @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <input class="input100 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password" required autocomplete="current-password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                            @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <div class="container-login100-form-btn">
                                    <button type="submit" class="login100-form-btn disable">
                                        {{ __('Se connecter') }}
                                    </button>
                                </div>

                                @if (Route::has('password.request'))
                                <div class="text-center p-t-30" style="padding-top: 10px">
                                    <a class="txt2" href="{{ route('password.request') }}">
                                        {{ __('Mot de passe oublié?') }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                      
                    </form>
                </div>
            </div>
@endsection
