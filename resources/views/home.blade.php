@extends('layouts.app')
@section('title')
    Tableau de bord
@endsection
@section('content')
<h3 class="page-title">
    Comparaison des tickets
</h3>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="icon-drawer"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php
                        echo number_format($ticket_pm,0,",",".");
                    ?>
                </div>
                <div class="desc">
                        Tickets PM
                </div>
            </div>
            <a class="more" href="{{ route('ticket_pm') }}">
                Voir plus... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat red-intense">
            <div class="visual">
                <i class="icon-drawer"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php
                        echo number_format($ticket_cm,0,",",".");
                    ?>
                </div>
                <div class="desc">
                    Ticket CM
                </div>
            </div>
            <a class="more" href="{{ route('ticket_cm') }}">
                Voir plus... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat green-haze">
            <div class="visual">
                <i class="icon-drawer"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php
                        echo number_format($autres_tickets,0,",",".");
                    ?>
                </div>
                <div class="desc">
                    Autres tickets
                </div>
            </div>
            <a class="more" href="{{ route('autres_tickets') }}">
                Voir plus... <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>

<!-- Nouveau bloc pour le graphique -->
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bar-chart font-green-haze"></i>
                    <span class="caption-subject bold uppercase">Répartition des tickets d'aujourd'hui</span>
                </div>
            </div>
            <div class="portlet-body">
                <canvas id="ticketsChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ticketsChart').getContext('2d');
    let ticketsChart = null;

    function createChart(data) {
        if (ticketsChart) {
            ticketsChart.destroy();
        }

        ticketsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Nombre de tickets',
                    data: [
                        data.data.ticket_cm,
                        data.data.ticket_pm,
                        data.data.autres_tickets
                    ],
                    backgroundColor: data.colors,
                    borderColor: data.borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Nombre de tickets: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }

    function updateChart() {
        fetch('/tickets-stats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    createChart(data);
                    
                    // Mise à jour des compteurs en haut
                    document.querySelector('.dashboard-stat.blue-madison .number').textContent = 
                        new Intl.NumberFormat('fr-FR').format(data.data.ticket_pm);
                    
                    document.querySelector('.dashboard-stat.red-intense .number').textContent = 
                        new Intl.NumberFormat('fr-FR').format(data.data.ticket_cm);
                    
                    document.querySelector('.dashboard-stat.green-haze .number').textContent = 
                        new Intl.NumberFormat('fr-FR').format(data.data.autres_tickets);
                } else {
                    console.error('Erreur lors de la récupération des données');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }

    // Première mise à jour
    updateChart();

    // Mise à jour toutes les minutes
    setInterval(updateChart, 60000);
});
</script>
@endpush
