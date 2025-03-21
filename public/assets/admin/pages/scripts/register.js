//VALIDATION DU FORMULAIRE DE REGISTER
var Register = function() {

    var handleRegister = function() {

        $('.register-form-connect').validate({
            errorElement: 'span', // Utilise des balises <span> pour afficher les erreurs
            errorClass: 'help-block', // Classe pour le message d'erreur
            focusInvalid: false, // Ne pas mettre le focus sur le dernier champ invalide
            rules: {
                nom: {
                    required: true,
                },
                telephone_membre: {
                    required: true,
                },
                genre_id: {
                    required: true,
                },
                situationmatrimoniale_id: {
                    required: true,
                },
                trancheage_id: {
                    required: true,
                },
                profession: {
                    required: true,
                },
                commune: {
                    required: true,
                },
                quartier: {
                    required: true,
                },
                telephone: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    required: true,
                    minlength: 8,
                    equalTo: '#password'
                },
                remember: {
                    required: false
                }
            },

            messages: {
                nom: {
                    required: "Le nom et prénoms est obligatoire.",
                },
                telephone_membre: {
                    required: "Le numéro de téléphone est obligatoire.",
                },
                genre_id: {
                    required: "Le sexe est obligatoire.",
                },
                situationmatrimoniale_id: {
                    required: "La situation matrimoniale est obligatoire.",
                },
                trancheage_id: {
                    required: "Le tranche d'âge est obligatoire.",
                },
                profession: {
                    required: "La profession est obligatoire.",
                },
                commune: {
                    required: "La commune est obligatoire.",
                },
                quartier: {
                    required: "Le quartier est obligatoire.",
                },
                telephone: {
                    required: "Le login est obligatoire.",
                },
                password: {
                    required: "Mot de passe obligatoire.",
                    minlength: "Entrer un mot de passe d'au moins 8 caractères.",
                },
                password_confirmation: {
                    required: "La confirmation du mot de passe obligatoire.",
                    minlength: "Entrer un mot de passe d'au moins 8 caractères.",
                    equalTo: "La confirmation du mot de passe n'est conforme au mot de passe.",
                },
            },

            invalidHandler: function(event, validator) { // Affiche une alerte d'erreur lors de la soumission du formulaire   
                $('.alert-danger', $('.register-form-connect')).show();
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

        $('.register-form-connect input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.register-form-connect').validate().form()) {
                    $('.register-form-connect').submit(); // Validation du formulaire réussie, soumettre le formulaire en AJAX
                }
                return false;
            }
        });
    }

    return {
        // Fonction principale pour initialiser le module
        init: function() {
            handleRegister();
        }

    };

}();

$(document).ready(function() {
    Register.init();
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