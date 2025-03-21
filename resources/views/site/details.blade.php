@extends('layouts.app')
@section('title')
    <?php echo(html_entity_decode($site->site_ihs)) ?> - <?php echo(html_entity_decode($site->site_nom)) ?>
@endsection
@section('content')
<h3 class="page-title">
    <?php echo(html_entity_decode($site->site_ihs)) ?> - <?php echo(html_entity_decode($site->site_nom)) ?>
</h3>
<div class="page-bar">
   <ul class="page-breadcrumb">
      <li>
         <i class="fa fa-home"></i>
         <a href="{{ route('home') }}">Home</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des sites</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Détails : <?php echo(html_entity_decode($site->site_ihs)) ?></a>
      </li>
   </ul> 
   <div class="page-toolbar">
      <a href="{{ route('liste_site') }}">
         <div id="dashboard-report-range" class="tooltips btn btn-fit-height btn-sm green-haze btn-dashboard-daterange">
            <i class="icon-arrow-left"></i>
            Retour à la liste
         </div>
      </a>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        @include('includes.site')
        <div class="tabbable tabbable-custom tabbable-noborder">
            @include('includes.menu_site')
            <div class="tab-content">
                <div class="tab-pane @if(substr($_SERVER['REQUEST_URI'], 0,  32) == '/gestion-des-sites/details-site/') active @endif">
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <form action="{{ route('details_site',[$site->site_id, Stdfn::clean_url(html_entity_decode($site->site_ihs))]) }}" method="GET">
                                    <br>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="c">Code</label>
                                            <input type="text" class="form-control" id="c" name="c" value="{{ $selected_code }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label for="t">Type d'action</label>
                                        <select name="t" id="t" class="form-control o" data-placeholder="Choisir" tabindex="1">
                                            <option value="">Choisir</option>
                                            @foreach ($typeactions as $typeaction)
                                                <option value="{{ $typeaction->type_action_id }}" {{ $selected_typeaction == $typeaction->type_action_id ? 'selected' : '' }}>{{ $typeaction->type_action_nom }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="d">Date de déclaration</label>
                                            <input type="date" class="form-control" id="d" name="d" value="{{ $selected_datedeclaration }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="margin-top:25px;">
                                    <a href="{{ route('details_site',[$site->site_id, Stdfn::clean_url(html_entity_decode($site->site_ihs))]) }}" class="btn btn-success" style="float:right"><i class="fa fa-refresh"></i></a>
                                        <button type="submit" class="btn btn-primary mr-5" style="float:right"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <hr>
                        </div>
                        <div class="">
                            <table class="table table-striped table-bordered table-hover" id="sample_3">
                                <thead>
                                    <tr>
                                        <th class="d-none"></th>
                                        <th>Code</th>
                                        <th>Site</th>
                                        <th>Type action</th>
                                        <th>Date & Heure de début</th>
                                        <th>Date & Heure de fin</th>
                                        <th>Date de déclaration</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td class="d-none"></td>
                                            <td>{{ $ticket->ticket_code }}</td>
                                            <td>{{ $ticket->site_nom }}</td>
                                            <td>{{ $ticket->type_action_nom }}</td>
                                            <td>{{ Stdfn::dateFromDB($ticket->ticket_datedebut) }} à {{ $ticket->ticket_heuredebut }}</td>
                                            <td>{{ Stdfn::dateFromDB($ticket->ticket_datefin) }} à {{ $ticket->ticket_heurefin }}</td>
                                            <td>{{ Stdfn::dateFromDB($ticket->ticket_datedeclaration) }}</td>
                                            <td class="text-center action_button">
                                                <a href="{{ route('details_ticket',[$ticket->site_id, Stdfn::clean_url(html_entity_decode($ticket->ticket_code))]) }}"><img src="{{ asset('assets/admin/images/icon/details.png') }}" width="20"></a>
                                                @if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001"))
                                                    <a href="{{ route('modifier_ticket', $ticket->site_id) }}" title="Modifier" ><img src="{{ asset('assets/admin/images/icon/modifier.png') }}" width="20"></a>
                                                    <span class="btnSupprimerTicket" data-ticket_id="{{ $ticket->site_id }}" title="Supprimer"><img src="{{ asset('assets/admin/images/icon/supprimer.png') }}" width="20"></span>
                                                @endif
                                            </td> 
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
