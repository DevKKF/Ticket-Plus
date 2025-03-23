@extends('layouts.app')
@section('title')
    Gestion des régions
@endsection
@section('content') 
<h3 class="page-title">
   Gestion des régions
</h3>
<div class="page-bar">
   <ul class="page-breadcrumb">
      <li>
         <i class="fa fa-home"></i>
         <a href="{{ route('home') }}">Home</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des paramètres</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des régions</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-settings font-20"></i>Liste des régions
            </div>
            <div class="actions">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#AjouterRegion"><i class="fa fa-plus-circle"></i> Nouvelle région</button>
            </div>
         </div>
         <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_3">
                <thead>
                    <tr>
                        <th class="d-none"></th>
                        <th>Nom</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($regions as $region)
                        <tr>
                            <td class="d-none"></td>
                            <td>{{ $region->region_nom }}</td>
                            <td><span class="badge badge-<?php echo(str_replace(' ','', $region->region_statut)); ?>">{{ $region->region_statut }}</span></td>
                            <td>{{ Stdfn::dateFromDB($region->region_datecrea) }}</td>
                            <td class="text-center action_button">
                                <span data-toggle="modal" title="Modifier" data-target="#EditRegion{{ $region->region_id }}"><img src="{{ asset('assets/admin/images/icon/modifier.png') }}" width="20"></span>
                                <span class="btnSupprimerRegion" data-region_id="{{ $region->region_id }}" title="Supprimer"><img src="{{ asset('assets/admin/images/icon/supprimer.png') }}" width="20"></span>
                            </td> 
                        </tr>
                    @endforeach   
                </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@foreach($regions as $region)
    <div class="modal fade" id="EditRegion{{ $region->region_id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modifier la région</h4>
                </div>
                <form method="POST" action="{{ route('modifier_region', $region->region_id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body"> 
                        <div class="form-group">
                            <label>Nom de la région <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="nom" class="form-control" value="{{ $region->region_nom }}" required>
                        </div> 
                        <div class="form-group">
                            <label>Statut <span class="text-danger">*</span></label>
                            <select name="statut" id="statut" class="form-control" required>
                                <option value="">Choisir</option>
                                <option value="BROUILLON" {{ $region->region_statut == 'BROUILLON' ? 'selected' : '' }}>BROUILLON</option>
                                <option value="VALIDE" {{ $region->region_statut == 'VALIDE' ? 'selected' : '' }}>VALIDE</option>
                            </select>
                        </div>   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" style="float: left !important;" data-dismiss="modal" style=""><i class="fa fa-remove"></i> Fermer</button>
                        <button type="submit" class="btn btn-success" style="float: right !important;" id="formSubmit"><i class="fa fa-check-circle"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach 

<div class="modal fade" id="AjouterRegion" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Ajouter une nouvelle région</h4>
            </div>
            <form method="POST" action="{{ route('save_region') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom de la région <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Statut <span class="text-danger">*</span></label>
                        <select name="statut" id="statut" class="form-control" required>
                            <option value="">Choisir</option>
                            <option value="BROUILLON">BROUILLON</option>
                            <option value="VALIDE">VALIDE</option>
                        </select>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" style="float: left !important;" data-dismiss="modal" style=""><i class="fa fa-remove"></i> Fermer</button>
                    <button type="submit" class="btn btn-success" style="float: right !important;" id="formSubmit"><i class="fa fa-check-circle"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection