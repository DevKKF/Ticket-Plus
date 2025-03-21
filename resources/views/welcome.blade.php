@extends('layouts.auth')
@section('title')
    Se connecter
@endsection
@section('content')
<style>
    .connecter-avec {
        display: flex;
        justify-content: center;
    }

    .social-icons {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
    }

    .social-icons li {
        margin: 0 3px;
    }

    .social-icons li:first-child {
        margin-left: 0;
    }

    .social-icons li:last-child {
        margin-right: 0;
    }

    .social-icon-color {
        display: block;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #ccc;
    }

    .facebook:before {
        content: "Facebook";
    }

    .twitter:before {
        content: "Twitter";
    }

    .googleplus:before {
        content: "Google Plus";
    }

    .linkedin:before {
        content: "Linkedin";
    }
</style>
<form class="login-form-connect" action="{{ route('login')}}" method="post">
    @csrf
    <h3 class="form-title uppercase" style="font-size: 22px;">Connexion</h3>
    @if (session('echec'))
        <div id="prefix_1152407560663" class="Metronic-alerts alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>{{ session('echec') }}</div>
    @endif
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Login</label>
        <input class="form-control form-control-solid placeholder-no-fix @error('login') is-invalid @enderror" type="login" placeholder="Login" name="login" value="{{ old('login') }}">
        <span class="error"></span>
        @error('login')
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Mot de passe</label>
        <input class="form-control form-control-solid placeholder-no-fix @error('password') is-invalid @enderror" type="password" placeholder="Mot de passe" name="password">
        <span class="error"></span>
        @error('password')
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <!--div class="form-group d-flex justify-content-between">
        @if (Route::has('password.request'))
            <center>
                <a href="{{ route('password.change') }}" style="margin: auto; font-size: 15px;">Mot de passe oublié</a>
            </center>
        @endif
    </div-->
    <div class="form-actions mt-10" style="">
        <button type="submit" class="btn btn-success btn-block uppercase">Se connecter</button>
    </div>
</form>
@endsection
