@extends('layouts.auth')
@section('title')
    Réinitialiser le mot de passe
@endsection
@section('content')
    <form id="new-password" method="POST" action="{{ route('password.update.save') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $passwordReset->token }}">
    <h3 class="form-title uppercase" style="font-size: 22px;">Réinitialiser le mot de passe</h2>
    <div class="form-group">
        <div class="input-group-icon right">
            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email" value="{{ $passwordReset->email ?? old('email') }}" autocomplete="email" readonly>
            <span class="error"></span>
            @error('email')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group">
        <div class="input-group-icon right">
            <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" placeholder="Mot de passe" autocomplete="current-password">
            <span class="error"></span>
            @error('password')
                <span class="invalid-feedback text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror 
        </div>
    </div>
    <div class="form-group">
        <div class="input-group-icon right">
            <input class="form-control" type="password" id="password-confirm" name="password_confirmation" placeholder="Confirmer mot de passe" autocomplete="new-password">
            <span class="error"></span>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success btn-block uppercase">Réinitialiser le mot de passe</button>
    </div>
</form>
<script>
    var ResetPassword = function() {

        var handleResetPassword = function() {

            $('#new-password').validate({
                errorElement: 'span', // Utilise des balises <span> pour afficher les erreurs
                errorClass: 'help-block', // Classe pour le message d'erreur
                focusInvalid: false, // Ne pas mettre le focus sur le dernier champ invalide
                rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
                    }
                },

                messages: {
                    email: {
                        required: "L'adresse mail est obligatoire.",
                        email: "L'adresse mail n'est pas valide.",
                    },
                    password: {
                        required: "Mot de passe obligatoire.",
                        minlength: "Entrer un mot de passe obligatoire d'au moins 8 caractères.",
                    },
                    password_confirmation: {
                        required: "Confirmation du mot de passe obligatoire.",
                        equalTo: "Les mots de passe ne correspondent pas.",
                    }
                },

                invalidHandler: function(event, validator) { // Affiche une alerte d'erreur lors de la soumission du formulaire   
                    $('.alert-danger', $('#new-password')).show();
                },

                highlight: function(element) { // Met en évidence les champs en erreur
                    $(element)
                        .closest('.form-group').addClass('has-error'); // Ajoute la classe d'erreur au groupe de contrôle
                },

                success: function(label) {
                    label.closest('.form-group').removeClass('has-error');
                    label.remove();
                },

                errorPlacement: function(error, element) {
                    error.insertAfter(element); // Insère le message d'erreur après le champ d'entrée
                },

                submitHandler: function(form) {
                    form.submit(); // Validation du formulaire réussie, soumettre le formulaire en AJAX
                }
            });

            $('#new-password input').keypress(function(e) {
                if (e.which == 13) {
                    if ($('#new-password').validate().form()) {
                        $('#new-password').submit(); // Validation du formulaire réussie, soumettre le formulaire en AJAX
                    }
                    return false;
                }
            });
        }

        return {
            // Fonction principale pour initialiser le module
            init: function() {
                handleResetPassword();
            }

        };

    }();

    $(document).ready(function() {
        ResetPassword.init();
    });
</script>
@endsection