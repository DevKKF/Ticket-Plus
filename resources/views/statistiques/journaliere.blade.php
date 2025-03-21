@extends('layouts.app')
@section('title')
    Statistiques journalière
@endsection
@section('content')
<style>
    .barre {
        height: 30px;
        margin-bottom: 5px;
        color: white;
        text-align: right;
        padding-right: 10px;
        line-height: 30px;
    }

    .repere {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        padding: 0 10px;
    }

    .marque {
        font-size: 12px;
        color: #888;
    }

</style>
<h3 class="page-title">
   Statistiques journalière
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ route('home') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li class="sous-menu">
            <a href="javascript::void(0)">Statistiques</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li class="sous-menu">
            <a href="javascript::void(0)">Statistiques journalière</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="portlet box tiketplus">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-bar-chart font-20"></i>Statistiques journalière
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a class="btn btn-success btn-sm" href="javascript:;" data-toggle="dropdown">
                            <i class="icon-folder-alt"></i> Exporter <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="">
                                <i class="fa fa-file-excel-o"></i> Exporter fichier CSV</a>
                            </li>
                            <li>
                                <a href="">
                                <i class="fa fa-file-excel-o"></i> Exporter fichier Excel</a>
                            </li>
                            <li>
                                <a href="">
                                <i class="fa fa-file-pdf-o"></i> Exporter fichier PDF</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <form  method="POST" id="ajax_statistiques_journaliere" action="{{ route('ajax_statistiques_journaliere') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Site</label>
                                    <select name="site_id" id="site_id" class="form-control select_search">
                                        <option value="">Choisir</option>
                                        @foreach ($sites as $site)
                                            <option value="{{ $site->site_id }}" {{ old('site_id') == $site->site_id ? 'selected' : '' }}>{{ $site->site_ihs }} - {{ $site->site_nom }} - {{ $site->zone_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Type d'action</label>
                                    <select name="type_action_id" id="type_action_id" class="form-control">
                                        <option value="">Choisir</option>
                                        @foreach ($typeactions as $typeaction)
                                            <option value="{{ $typeaction->type_action_id }}" {{ old('type_action_id') == $typeaction->type_action_id ? 'selected' : '' }}>{{ $typeaction->type_action_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                        </hr>
                        <hr>
                        <br>
                    </div>
                </div>
                <!--div id="graphique-container">
                    <canvas id="monGraphique" width="400" height="200"></canvas>
                </div-->
                <div id="graphique-container">
                    <canvas id="monGraphique"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Fonction pour déclencher la requête AJAX et construire le graphique
        function chargerStatistiques() {
            var form = $('#ajax_statistiques_journaliere');
            var url = form.attr('action');
            var data = form.serialize();
            
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    // Affiche un message de chargement
                    $('#graphique-container').html('<canvas id="monGraphique"></canvas>');
                },
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            construireGraphique(response.data);
                        } else {
                            $('#graphique-container').html('<p>Aucune donnée trouvée.</p>');
                        }
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                },
                error: function() {
                    alert('Erreur lors de la requête AJAX.');
                    $('#graphique-container').html('<p>Erreur lors du chargement des données.</p>');
                }
            });
        }

        // Écoute les changements sur les sélecteurs
        $('#site_id, #type_action_id').on('change', function() {
            chargerStatistiques();
        });

        // Fonction pour construire le graphique avec Chart.js
        function construireGraphique(donnees) {
            var ctx = document.getElementById('monGraphique').getContext('2d');

            // Prépare les données pour Chart.js
            var labels = donnees.map(item => item.utilisateur.nom_prenoms);
            var data = donnees.map(item => item.pourcentage);
            var backgroundColors = donnees.map(() => getRandomColor()); // Génère des couleurs aléatoires

            new Chart(ctx, {
                type: 'bar', // Type de graphique : barres
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pourcentage',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true, // Commence l'axe Y à 0
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)' // Couleur du quadrillage
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%'; // Ajoute '%' à l'axe Y
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)' // Couleur du quadrillage
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel + '%'; // Ajoute '%' aux info-bulles
                            }
                        }
                    }
                }
            });
        }

        // Fonction pour générer une couleur aléatoire
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    });
</script>
@endsection
