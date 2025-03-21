<!-- reset_password_email.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Styles CSS pour le logo et la signature */
        .logo {
            /* Styles pour le logo */
        }

        .signature {
            /* Styles pour la signature */
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="{{ asset('path/to/logo.png') }}" alt="Logo">
    </div>

    <p>
        Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.
    </p>

    <p>
        Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :
        <a href="{{ route('password-change-save', $token) }}">Réinitialiser le mot de passe</a>
    </p>

    <p>
        Ce lien de réinitialisation de mot de passe expirera dans minutes.
    </p>

    <p>
        Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action supplémentaire n'est requise.
    </p>

    <div class="signature">
        <img src="{{ asset('path/to/signature.png') }}" alt="Signature">
    </div>
</body>
</html>
