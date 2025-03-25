@extends('layouts.app')
@section('title')
    Liste des utilisateurs
@endsection
@section('content') 
<h3 class="page-title">
   Liste des utilisateurs
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
         <a href="#">Liste des utilisateurs</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-users font-20"></i>Liste des utilisateurs
            </div>
            <div class="actions">
            @if(in_array(Auth::user()->profil_id, [1, 2]))
               <a class="btn btn-success btn-sm" href="{{ route('ajouter_utilisateur') }}"><i class="fa fa-plus-circle"></i> Nouveau utilisateur</a>
               <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ImporterUtilisateur"><i class="fa fa-users"></i> Importer des techniciens</button>
            @endif 
            </div>
         </div>
         <div class="portlet-body">
            <div class="table-toolbar">
               <div class="row">
                  <form action="{{ route('liste_utilisateur') }}" method="GET">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="c">Nom ou Prénoms</label>
                           <input type="text" class="form-control" id="np" name="np" value="{{ $selected_nom_prenoms }}">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="c">Niveau d'accès</label>
                           <select name="p" id="p" class="form-control p" data-placeholder="Choisir" tabindex="1">
                              <option value="">Choisir</option>
                              @foreach ($profils as $profil)
                                    <option value="{{ $profil->profil_id }}" {{ $selected_profil == $profil->profil_id ? 'selected' : '' }}>{{ $profil->profil_nom }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="d">Date de création</label>
                           <input type="date" class="form-control" id="d" name="d" value="{{ $selected_datecreation }}">
                        </div>
                     </div>
                     <div class="col-md-2" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary" style="float:left"><i class="fa fa-search"></i></button>
                        <a href="{{ route('liste_utilisateur') }}" class="btn btn-success" style="float:right"><i class="fa fa-refresh"></i></a>
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
                        <th>Nom et Prénoms</th>
                        <th>Niveau d'accès</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th class="text-center">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($utilisateurs as $utilisateur)
                           <tr>
                              <td class="d-none"></td>
                              <td><?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?></td>
                              <td><span class="badge badge-<?php echo(str_replace(' ','', $utilisateur->profil_color)); ?>"><?php echo(html_entity_decode($utilisateur->profil_nom)) ?></span></td>
                              <td><span class="badge badge-<?php echo(str_replace(' ','', $utilisateur->user_statut)); ?>">{{ $utilisateur->user_statut }}</span></td>
                              <td>{{ Stdfn::dateFromDB($utilisateur->created_at) }}</td>
                              <td class="text-center action_button">
                                 <a href="{{ route('details_utilisateur',[$utilisateur->id, Stdfn::clean_url(html_entity_decode($utilisateur->nom_prenoms))]) }}"><img src="{{ asset('assets/admin/images/icon/details.png') }}" width="20"></a>
                                 @if(in_array(Auth::user()->profil_id, [1, 2]))
                                    <a href="{{ route('modifier_utilisateur', $utilisateur->id) }}" title="Modifier" ><img src="{{ asset('assets/admin/images/icon/modifier.png') }}" width="20"></a>
                                    <span class="btnSupprimerUtilisateur" data-utilisateur_id="{{ $utilisateur->id }}" title="Supprimer"><img src="{{ asset('assets/admin/images/icon/supprimer.png') }}" width="20"></span>
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

@include('utilisateur.modal_importer_utilisateur')

@endsection