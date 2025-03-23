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
@endsection
