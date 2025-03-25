@extends('layouts.app')
@section('title')
    Liste des tickets
@endsection
@section('content') 
<h3 class="page-title">
   Liste des tickets
</h3>
<div class="page-bar">
   <ul class="page-breadcrumb">
      <li>
         <i class="fa fa-home"></i>
         <a href="{{ route('home') }}">Home</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des tickets</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Liste des tickets</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-globe font-20"></i>Liste des tickets
            </div>
            <div class="actions">
               @if(in_array(Auth::user()->profil_id, [1, 2]))
                  <a class="btn btn-success btn-sm" href="{{ route('ajouter_ticket') }}"><i class="fa fa-plus-circle"></i> Nouveau ticket</a>
               @endif
               <div class="btn-group">
                  <a class="btn btn-success btn-sm" href="javascript:;" data-toggle="dropdown">
                        <i class="icon-folder-alt"></i> Exporter <i class="fa fa-angle-down"></i>
                  </a>
                  <ul class="dropdown-menu pull-right">
                        <li>
                           <a href="{{ route('tickets.export.csv') }}">
                           <i class="fa fa-file-excel-o"></i> Exporter fichier CSV</a>
                        </li>
                        <li>
                           <a href="{{ route('tickets.export.excel') }}">
                           <i class="fa fa-file-excel-o"></i> Exporter fichier Excel</a>
                        </li>
                        <li>
                           <a href="{{ route('tickets.export.pdf') }}">
                           <i class="fa fa-file-pdf-o"></i> Exporter fichier PDF</a>
                        </li>
                  </ul>
               </div>
            </div>
         </div>
         <div class="portlet-body">
            <div class="table-toolbar">
               <div class="row">
                  <form action="{{ route('liste_ticket') }}" method="GET">
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="c">Code</label>
                           <input type="text" class="form-control" id="c" name="c" value="{{ $selected_code }}">
                        </div>
                     </div>
                     <div class="col-md-3">
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
                     <div class="col-md-3">
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
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="d">Date de déclaration</label>
                           <input type="date" class="form-control" id="d" name="d" value="{{ $selected_datedeclaration }}">
                        </div>
                     </div>
                     <div class="col-md-2" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary" style="float:left"><i class="fa fa-search"></i></button>
                        <a href="{{ route('liste_ticket') }}" class="btn btn-success" style="float:right"><i class="fa fa-refresh"></i></a>
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
                              <a href="{{ route('details_ticket',[$ticket->ticket_id, Stdfn::clean_url(html_entity_decode($ticket->ticket_code))]) }}"><img src="{{ asset('assets/admin/images/icon/details.png') }}" width="20"></a>
                              @if(in_array(Auth::user()->profil_id, [1, 2]))
                                 <a href="{{ route('modifier_ticket', $ticket->ticket_id) }}" title="Modifier" ><img src="{{ asset('assets/admin/images/icon/modifier.png') }}" width="20"></a>
                                 <span class="btnSupprimerTicket" data-ticket_id="{{ $ticket->ticket_id }}" title="Supprimer"><img src="{{ asset('assets/admin/images/icon/supprimer.png') }}" width="20"></span>
                              @endif
                              @if(Auth::user()->profil_id == 3)
                                 <span data-toggle="modal" title="Demande d'action" data-target="#DemandeActionTicket{{ $ticket->ticket_id }}"><img src="{{ asset('assets/admin/images/icon/demande.png') }}" width="20"></span>
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
@include('ticket.modal_demande_action')
@endsection