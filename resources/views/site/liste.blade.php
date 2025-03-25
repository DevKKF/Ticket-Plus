@extends('layouts.app')
@section('title')
    Liste des sites
@endsection
@section('content') 
<h3 class="page-title">
   Liste des sites
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
         <a href="#">Liste des sites</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-globe font-20"></i>Liste des sites
            </div>
            <div class="actions">
               @if(in_array(Auth::user()->profil_id, [1, 2]))
                  <a class="btn btn-success btn-sm mr-5" href="{{ route('ajouter_site') }}"><i class="fa fa-plus-circle"></i> Nouveau site</a>
                  <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#ImporterSite">
                     <i class="icon-globe"></i> Importer des sites
                  </button>
               @endif
               <div class="btn-group">
                  <a class="btn btn-success btn-sm" href="javascript:;" data-toggle="dropdown">
                  <i class="icon-folder-alt"></i> Exporter <i class="fa fa-angle-down"></i>
                  </a>
                  <ul class="dropdown-menu pull-right">
                     <li>
                        <a href="{{ route('sites.export.csv') }}" target="_blank">
                        <i class="fa fa-file-excel-o"></i> Exporter fichier CSV</a>
                     </li>
                     <li>
                        <a href="{{ route('sites.export.excel') }}">
                        <i class="fa fa-file-excel-o"></i> Exporter fichier Excel</a>
                     </li>
                     <li>
                        <a href="{{ route('sites.export.pdf') }}" target="_blank">
                        <i class="fa fa-file-pdf-o"></i> Exporter fichier PDF</a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <div class="portlet-body">
            <div class="table-toolbar">
               <div class="row">
                  <form action="{{ route('liste_site') }}" method="GET">
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="c">Code</label>
                           <input type="text" class="form-control" id="c" name="c" value="{{ $selected_code }}">
                        </div>
                     </div>
                     <div class="col-md-3">
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
                     <div class="col-md-3">
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
                     <div class="col-md-2">
                        <div class="form-group">
                           <label for="d">Date de création</label>
                           <input type="date" class="form-control" id="d" name="d" value="{{ $selected_datecreation }}">
                        </div>
                     </div>
                     <div class="col-md-2" style="margin-top: 25px;">
                        <button type="submit" class="btn btn-primary" style="float:left"><i class="fa fa-search"></i></button>
                        <a href="{{ route('liste_site') }}" class="btn btn-success" style="float:right"><i class="fa fa-refresh"></i></a>
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
                        <th>SBC</th>
                        <th>Date de création</th>
                        <th class="text-center">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($sites as $site)
                        <tr>
                           <td class="d-none"></td>
                           <td>{{ $site->site_ihs }}</td>
                           <td>{{ $site->region_nom }}</td>
                           <td>{{ $site->zone_nom }}</td>
                           <td>{{ $site->site_nom }}</td>
                           <td>{{ $site->operateur_nom }}</td>
                           <td>{{ $site->site_sbc }}</td>
                           <td>{{ Stdfn::dateFromDB($site->site_date_creation) }}</td>
                           <td class="text-center action_button">
                              <a href="{{ route('details_site',[$site->site_id, Stdfn::clean_url(html_entity_decode($site->site_ihs))]) }}"><img src="{{ asset('assets/admin/images/icon/details.png') }}" width="20"></a>
                              @if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002"))
                                 <a href="{{ route('modifier_site', $site->site_id) }}" title="Modifier" ><img src="{{ asset('assets/admin/images/icon/modifier.png') }}" width="20"></a>
                                 <span class="btnSupprimerSite" data-site_id="{{ $site->site_id }}" title="Supprimer"><img src="{{ asset('assets/admin/images/icon/supprimer.png') }}" width="20"></span>
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
@include('site.modal_importer_site')
@endsection