@extends('layouts.app')
@section('title')
   Liste des demandes d'action sur les tickets
@endsection
@section('content') 
<h3 class="page-title">
   Liste des demandes d'action sur les tickets
</h3>
<div class="page-bar">
   <ul class="page-breadcrumb">
      <li>
         <i class="fa fa-home"></i>
         <a href="{{ route('home') }}">Home</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des demandes</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Liste des demandes</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-globe font-20"></i>Liste des demandes d'action sur les tickets
            </div>
            <div class="actions">
               
            </div>
         </div>
         <div class="portlet-body">
            <div class="table-toolbar">
               <div class="row">
                  <form action="{{ route('liste_des_demandes') }}" method="GET">
                     <div class="col-md-3">
                        <div class="form-group">
                           <label for="u">Utilisateur</label>
                           <select name="u" id="u" class="select_search form-control s" data-placeholder="Choisir" tabindex="1">
                                 <option value="">Choisir</option>
                                 @foreach ($utilisateurs as $utilisateur)
                                    <option value="{{ $utilisateur->id }}" {{ $selected_utilisateur == $utilisateur->id ? 'selected' : '' }}>{!! $utilisateur->nom_prenoms !!}</option>
                                 @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="form-group">
                           <label>Type de demande</label>
                           <select name="ta" id="ta" class="form-control">
                              <option value="">Choisir</option>
                              @foreach($actionticket as $action)
                                 <option value="{{ $action->action_ticket_id }}" {{ $selected_action == $action->action_ticket_id ? 'selected' : '' }}>{{ $action->action_ticket_nom }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label>Statut</label>
                           <select name="s" id="s" class="form-control">
                              <option value="">Choisir</option>
                              
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="d">Date de déclaration</label>
                           <input type="date" class="form-control" id="d" name="d" value="{{ $selected_datedemande }}">
                        </div>
                     </div>
                     <div class="col-md-2" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary" style="float:left"><i class="fa fa-search"></i></button>
                        <a href="{{ route('liste_des_demandes') }}" class="btn btn-success" style="float:right"><i class="fa fa-refresh"></i></a>
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
                        <th>Demandeur</th>
                        <th>Type de demande</th>
                        <th>Demande à traiter</th>
                        <th>Statut</th>
                        <th>Date de demande</th>
                        <th class="text-center">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($demandes as $demande)
                        <tr>
                           <td class="d-none"></td>
                           <td>{!! $demande->nom_prenoms !!}</td>
                           <td>{{ $demande->action_ticket_nom }}</td>
                           <td>{!! $demande->demande_a_traiter !!}</td>
                           <td><span class="badge badge-<?php echo(str_replace(' ','', $demande->demande_statut)); ?>">{{ $demande->demande_statut }}</span></td>
                           <td>{{ Stdfn::dateFromDB($demande->demande_date) }}</td>
                           <td class="text-center action_button">
                              <a href="{{ route('details_demande',$demande->demande_id) }}"><img src="{{ asset('assets/admin/images/icon/details.png') }}" width="20"></a>
                              @if($demande->demande_statut == "EN COURS")
                                 <span data-toggle="modal" title="Traitement" data-target="#DemandeAction{{ $demande->demande_id }}"><img src="{{ asset('assets/admin/images/icon/demande.png') }}" width="20"></span>
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
@include('demande.modal_traitement')
@endsection