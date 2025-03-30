@extends('layouts.app')
@section('title')
    <?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?>
@endsection
@section('content')
<style>
   .text-green{
      color: green !important;
   }

   .text-danger{
      color: red !important;
   }
</style>
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
                        <span class="caption-subject font-blue-madison bold uppercase">Informations Générale</span>
                     </div>
                  </div>
                  <div class="portlet-body">
                     <div class="tab-content">
                        <!--div class="scroller" style="height: 320px; margin-top: 5px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2"-->
                           <div class="row static-info">
                              <div class="col-md-5 name">
                                 Nom & Prénoms : 
                              </div>
                              <div class="col-md-7 value">
                                 <?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?>
                              </div>
                           </div>  
                           <div class="row static-info">
                              <div class="col-md-5 name">
                                 Téléphone : 
                              </div>
                              <div class="col-md-7 value">
                                 {{ $utilisateur->telephone }}
                              </div>
                           </div>    
                           <div class="row static-info">
                              <div class="col-md-5 name">
                                 Autre téléphone : 
                              </div>
                              <div class="col-md-7 value">
                                 {{ $utilisateur->autre_telephone }}
                              </div>
                           </div>                         
                           <div class="row static-info">
                              <div class="col-md-5 name">
                                 Niveau d'accès :
                              </div>
                              <div class="col-md-7 value">
                                 <span class="badge badge-<?php echo(str_replace(' ','', $utilisateur->profil_color)); ?>">{{ $utilisateur->profil_nom }}</span>
                              </div>
                           </div>                        
                           <div class="row static-info">
                              <div class="col-md-5 name">
                                 Statut :
                              </div>
                              <div class="col-md-7 value">
                                 <span class="badge badge-<?php echo(str_replace(' ','', $utilisateur->user_statut)); ?>">{{ $utilisateur->user_statut }}</span>
                              </div>
                           </div>                        
                           <div class="row static-info">
                              <div class="col-md-5 name">
                                 Date de création :
                              </div>
                              <div class="col-md-7 value">
                                 {{ Stdfn::dateFromDB($utilisateur->created_at) }}
                              </div>
                           </div>
                           <hr>
                           <h3><b><u>Actions autorisées</u></b></h3>
                           <table class="table table-striped table-bordered table-hover">
                              <thead>
                                 <tr>
                                    <th class="d-none"></th>
                                    <th>Action</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Date de création</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($action_autorisees as $action)
                                    <tr>
                                       <td class="d-none"></td>
                                       <td>{!! html_entity_decode($action->action_nom) !!}</td>
                                       <td class="text-center">
                                          <i class="fa {{ $action->action_autorisee_statut == 'VALIDE' ? 'fa-toggle-on text-green' : 'fa-toggle-off text-danger' }} toggle-status"
                                             style="font-size:25px; cursor:pointer;"
                                             data-id="{{ $action->action_autorisee_id }}"></i>
                                       </td>
                                       <td class="text-center">{{ Stdfn::dateFromDB($action->action_autorisee_datecrea) }}</td>
                                    </tr>
                                    @endforeach
                              </tbody>
                           </table>
                        <!--/div-->
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection