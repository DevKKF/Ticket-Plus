@extends('layouts.auth')
@section('title')
    Mot de passe oublié
@endsection
@section('content')
<form id="forgot-form" action="{{ route('password.email.change') }}" method="post">
    @csrf
    <h3 class="form-title uppercase" style="font-size: 22px;">Mot de passe oublié</h3>
    @if (session('success'))
        <div id="prefix_1152407560663" class="Metronic-alerts alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>{{ session('success') }}</div>
    @endif
    
    <div class="form-group">
        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="email">
        <span class="error"></span>
        @error('email')
            <span class="invalid-feedback text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group">
        <button class="btn btn-success btn-block uppercase" type="submit">Envoyer le lien de réinitialisation</button>
    </div>
    <div class="social-auth-hr text-center">
        <a href="{{ route('login') }}"><span>Je me souviens de mon mot de passe</span></a>
    </div>
</form>
@endsection