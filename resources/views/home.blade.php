@extends('layouts.app')
@section('title')
    Tableau de bord
@endsection
@section('content')
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-light blue-soft" href="{{ route('ticket_pm') }}">
            <div class="visual">
                <i class="icon-pointer"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php
                        echo number_format($ticket_pm,0,",",".");
                    ?>
                </div>
                <div class="desc">
                    Nombre de PM du <?php echo(\Carbon\Carbon::parse(gmdate('Y-m-d'))->format('d/m/Y')) ?>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-light red-soft" href="{{ route('ticket_cm') }}">
            <div class="visual">
                <i class="icon-pointer"></i>
            </div>
            <div class="details">
                <div class="number">
                    <?php
                        echo number_format($ticket_cm,0,",",".");
                    ?>
                </div>
                <div class="desc">
                    Nombre de CM du <?php echo(\Carbon\Carbon::parse(gmdate('Y-m-d'))->format('d/m/Y')) ?>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
