@extends('layouts.app')
@section('title')
    <?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?>
@endsection
@section('content')
<h3 class="page-title">
   Détails : <?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?>
</h3>
<div class="page-bar">
   <ul class="page-breadcrumb">
      <li>
         <i class="fa fa-home"></i>
         <a href="{{ route('home') }}">Home</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des utilisateurs</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Détails : <?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?></a>
      </li>
   </ul> 
   <div class="page-toolbar">
      <a href="{{ route('liste_utilisateur') }}">
         <div id="dashboard-report-range" class="tooltips btn btn-fit-height btn-sm green-haze btn-dashboard-daterange">
            <i class="icon-arrow-left"></i>
            Retour à la liste
         </div>
      </a>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      @include('includes.utilisateur')
      <div class="profile-content">
         <div class="row">
            <div class="col-md-12">
               <div class="portlet light">
                  <div class="portlet-title tabbable-line" style="border-bottom: 2px solid #ccc !important;">
                     <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">Liste des tickets enregistrés</span>
                     </div>
                  </div>
                  <div class="portlet-body">
                     <div class="tab-content">
                        <div class="table-toolbar">
                           <div class="row">
                              <form action="{{ route('tickets_enregistres',[$utilisateur->id, Stdfn::clean_url(html_entity_decode($utilisateur->nom_prenoms))]) }}" method="GET">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="c">Code</label>
                                        <input type="text" class="form-control" id="c" name="c" value="{{ $selected_code }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="s">Site</label>
                                        <select name="s" id="s" class="select_search form-control s" data-placeholder="Choisir" tabindex="1">
                                            <option value="">Choisir</option>
                                            @foreach ($sites as $site)
                                                <option value="{{ $site->site_id }}" {{ $selected_site == $site->site_id ? 'selected' : '' }}>{{ $site->site_nom }} - {{ $site->zone_nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="d">Date de déclaration</label>
                                        <input type="date" class="form-control" id="d" name="d" value="{{ $selected_datedeclaration }}">
                                    </div>
                                </div>
                                 <div class="col-md-12" style="margin-top: 25px;">
                                    <a href="{{ route('tickets_enregistres',[$utilisateur->id, Stdfn::clean_url(html_entity_decode($utilisateur->nom_prenoms))]) }}" class="btn btn-success" style="float:right"><i class="fa fa-refresh"></i></a>
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
   </div>
</div>
@endsection