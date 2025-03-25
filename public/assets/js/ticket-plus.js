$(document).ready(function () {
    //gestion selection profil (ADMINISTRATEUR, UTILISATEUR)
    function manage_profil_change() {

        let profil_id = parseInt($('#profil_id').val());

        switch (profil_id) {
            default:
            case 2://ADMINISTRATEUR
                $('.site_choise').show();
                break;
            case 3://UTILISATEUR
                $('.site_choise').show();
                break;
        }
    }

    manage_profil_change();
    $(document).on('change', "#profil_id", function () {
        manage_profil_change();
    });


    //modification
    function manage_profil_change_modification() {

        let profil_id = parseInt($('#profil_id').val());

        switch (profil_id) {
            default:
            case 2://ADMINISTRATEUR
                $('.site_choise').hide();
                break;
            case 3://UTILISATEUR
                $('.site_choise').show();
                break;
        }
    }

    manage_profil_change_modification();
    $(document).on('change', "#profil_id", function () {
        manage_profil_change_modification();
    });


    // Gestion sélection site
    function manage_site_change() {
        let site_id = parseInt($('#site_id').val());

        if (site_id) {
            $('#bloc_info_site, #bloc_ticket').show();

            $.ajax({
                type: 'GET',
                url: '/chargement/site/' + site_id + '/info',
                success: function (site) {
                    // Remplir les champs avec les données reçues
                    $('#code_site').val(site.site_ihs || '');
                    $('#nom_site').val(site.site_nom || '');
                    $('#operateur_site').val(site.operateur_nom || '');
                    $('#zone_site').val(site.zone_nom || '');
                },
                error: function () {
                    console.error("Erreur lors du chargement du site.");
                }
            });
        } else {
            $('#bloc_info_site, #bloc_ticket').hide();

            // Réinitialiser les champs s'il n'y a pas de site sélectionné
            $('#code_site, #nom_site, #operateur_site, #zone_site').val('');
        }
    }

    // Initialisation au chargement de la page
    manage_site_change();

    // Déclencher la mise à jour lors du changement de site
    $(document).on('change', "#site_id", function () {
        manage_site_change();
    });



});


$(document).ready(function () {
    let region_id = $('#region_id').val(); // Région sélectionnée
    let selected_zone_id = $('#zone_id').data('selected'); // Zone déjà enregistrée pour le site

    if (region_id) {
        loadZones(region_id, selected_zone_id); // Charger les zones de la région active
    }

    $('#region_id').on('change', function () {
        let region_id = $(this).val();
        loadZones(region_id, null); // Charger les zones de la nouvelle région sélectionnée
    });

    function loadZones(region_id, selected_zone_id) {
        $('#zone_id').empty().append('<option value="">---------------------------</option>');

        if (region_id) {
            $.ajax({
                type: 'get',
                url: '/chargement/region/' + region_id + '/zone',
                success: function (zones) {
                    $('#zone_id').html('<option value="">Choisir</option>');

                    zones.forEach(function (zone) {
                        let selected = (zone.zone_id == selected_zone_id) ? 'selected' : ''; // Activer la zone enregistrée
                        $('#zone_id').append('<option value="' + zone.zone_id + '" ' + selected + '>' + zone.zone_nom + '</option>');
                    });
                },
                error: function () {
                    console.error("Erreur lors du chargement des zones.");
                }
            });
        }
    }
});


$(document).ready(function () {
    $('.toggle-status').on('click', function () {
        let actionId = $(this).data('id');
        let icon = $(this);
        let newStatus = icon.hasClass('fa-toggle-on') ? 'BROUILLON' : 'VALIDE'; // Inversion du statut

        $.ajax({
            url: '/action/update-statut/' + actionId,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Sécurité CSRF
                status: newStatus
            },
            success: function (response) {
                if (response.success) {
                    if (newStatus === 'VALIDE') {
                        icon.removeClass('fa-toggle-off text-danger').addClass('fa-toggle-on text-success');
                    } else {
                        icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-danger');
                    }
                } else {
                    alert("Une erreur est survenue !");
                }
            },
            error: function () {
                alert("Impossible de changer le statut !");
            }
        });
    });
});


$(document).ready(function () {
    let site_id = $('#site_id').val(); // Site sélectionnée
    let selected_user_id = $('#user_id').data('selected'); // User déjà enregistrée pour le site

    if (site_id) {
        loadUsers(site_id, selected_user_id); // Charger les users du site active
    }

    $('#site_id').on('change', function () {
        let site_id = $(this).val();
        loadUsers(site_id, null); // Charger les users du nouveau site sélectionnée
    });

    function loadUsers(site_id, selected_user_id) {
        $('#user_id').empty().append('<option value="">---------------------------</option>');

        if (site_id) {
            $.ajax({
                type: 'get',
                url: '/chargement/users/' + site_id + '/site',
                success: function (users) {
                    $('#user_id').html('<option value="">Choisir</option>');

                    users.forEach(function (user) {
                        let selected = (user.user_id == selected_user_id) ? 'selected' : ''; // Activer la user enregistrée
                        $('#user_id').append('<option value="' + user.user_id + '" ' + selected + '>' + user.nom_prenoms + '</option>');
                    });
                },
                error: function () {
                    console.error("Erreur lors du chargement des users.");
                }
            });
        }
    }
});
