//VALIDATION DU FORMULAIRE DE LOGIN
var Login = function() {

    var handleLogin = function() {

        $('.login-form-connect').validate({
            errorElement: 'span', // Utilise des balises <span> pour afficher les erreurs
            errorClass: 'help-block', // Classe pour le message d'erreur
            focusInvalid: false, // Ne pas mettre le focus sur le dernier champ invalide
            rules: {
                login: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 6,
                },
                remember: {
                    required: false
                }
            },

            messages: {
                login: {
                    required: "Le login est obligatoire.",
                },
                password: {
                    required: "Mot de passe obligatoire.",
                    minlength: "Entrer un mot de passe d'au moins 8 caractères.",
                }
            },

            invalidHandler: function(event, validator) { // Affiche une alerte d'erreur lors de la soumission du formulaire   
                $('.alert-danger', $('.login-form-connect')).show();
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

        $('.login-form-connect input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.login-form-connect').validate().form()) {
                    $('.login-form-connect').submit(); // Validation du formulaire réussie, soumettre le formulaire en AJAX
                }
                return false;
            }
        });
    }

    return {
        // Fonction principale pour initialiser le module
        init: function() {
            handleLogin();
        }

    };

}();

$(document).ready(function() {
    Login.init();
});


//VALIDATION DU FORMULAIRE DE MAIL DE REINITIALISATION
var ForgotPassword = function() {

    var handleForgotPassword = function() {

        $('#forgot-form').validate({
            errorElement: 'span', // Utilise des balises <span> pour afficher les erreurs
            errorClass: 'help-block', // Classe pour le message d'erreur
            focusInvalid: false, // Ne pas mettre le focus sur le dernier champ invalide
            rules: {
                email: {
                    required: true,
                    email: true,
                }
            },

            messages: {
                email: {
                    required: "L'adresse email est obligatoire.",
                    email: "L'adresse email n'est pas valide.",
                }
            },

            invalidHandler: function(event, validator) { // Affiche une alerte d'erreur lors de la soumission du formulaire   
                $('.alert-danger', $('#forgot-form')).show();
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

        $('#forgot-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('#forgot-form').validate().form()) {
                    $('#forgot-form').submit(); // Validation du formulaire réussie, soumettre le formulaire en AJAX
                }
                return false;
            }
        });
    }

    return {
        // Fonction principale pour initialiser le module
        init: function() {
            handleForgotPassword();
        }

    };

}();

$(document).ready(function() {
    ForgotPassword.init();
});


//VALIDATION DU FORMULAIRE DE REINITIALISATION DU MOT DE PASSE