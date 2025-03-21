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
                        <span class="caption-subject font-blue-madison bold uppercase">Liste des sites gérés</span>
                     </div>
                  </div>
                  <div class="portlet-body">
                     <div class="tab-content">
                        <div class="table-toolbar">
                           <div class="row">
                              <form action="{{ route('sites_geres',[$utilisateur->id, Stdfn::clean_url(html_entity_decode($utilisateur->nom_prenoms))]) }}" method="GET">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="c">Code</label>
                                       <input type="text" class="form-control" id="c" name="c" value="{{ $selected_code }}">
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="c">Zone</label>
                                       <select name="z" id="z" class="select_search form-control z" data-placeholder="Choisir" tabindex="1">
                                          <option value="">Choisir</option>
                                          @foreach ($zones as $zone)
                                                <option value="{{ $zone->zone_id }}" {{ $selected_zone == $zone->zone_id ? 'selected' : '' }}>{{ $zone->zone_nom }}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="o">Opérateur</label>
                                       <select name="o" id="o" class="form-control o" data-placeholder="Choisir" tabindex="1">
                                          <option value="">Choisir</option>
                                          @foreach ($operateurs as $operateur)
                                                <option value="{{ $operateur->operateur_id }}" {{ $selected_operateur == $operateur->operateur_id ? 'selected' : '' }}>{{ $operateur->operateur_nom }}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="d">Date de création</label>
                                       <input type="date" class="form-control" id="d" name="d" value="{{ $selected_datecreation }}">
                                    </div>
                                 </div>
                                 <div class="col-md-8" style="margin-top: 25px;">
                                 <a href="{{ route('sites_geres',[$utilisateur->id, Stdfn::clean_url(html_entity_decode($utilisateur->nom_prenoms))]) }}" class="btn btn-success" style="float:right"><i class="fa fa-refresh"></i></a>
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
                                    <th>Région</th>
                                    <th>Zone</th>
                                    <th>Nom</th>
                                    <th>Opérateur</th>
                                    <th class="text-center">Date de création</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($site_gerer as $site)
                                    <tr>
                                       <td class="d-none"></td>
                                       <td>{{ $site->site_ihs }}</td>
                                       <td>{{ $site->region_nom }}</td>
                                       <td>{{ $site->zone_nom }}</td>
                                       <td>{{ $site->site_nom }}</td>
                                       <td>{{ $site->operateur_nom }}</td>
                                       <td class="text-center">{{ Stdfn::dateFromDB($site->site_date_creation) }}</td>
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