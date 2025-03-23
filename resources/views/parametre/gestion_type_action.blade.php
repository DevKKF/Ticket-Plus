@extends('layouts.app')
@section('title')
    Gestion des types d'action
@endsection
@section('content') 
<h3 class="page-title">
   Gestion des types d'action
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
         <a href="#">Gestion des types d'action</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-settings font-20"></i>Liste des types d'action
            </div>
            <div class="actions">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#AjouterTypeAction"><i class="fa fa-plus-circle"></i> Nouveau type d'action</button>
            </div>
         </div>
         <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover" id="sample_3">
                <thead>
                    <tr>
                        <th class="d-none"></th>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($typeactions as $typeaction)
                        <tr>
                            <td class="d-none"></td>
                            <td>{{ $typeaction->type_action_code }}</td>
                            <td>{{ $typeaction->type_action_nom }}</td>
                            <td><span class="badge badge-<?php echo(str_replace(' ','', $typeaction->type_action_statut)); ?>">{{ $typeaction->type_action_statut }}</span></td>
                            <td>{{ Stdfn::dateFromDB($typeaction->type_action_datecrea) }}</td>
                            <td class="text-center action_button">
                                <span data-toggle="modal" title="Modifier" data-target="#EditTypeAction{{ $typeaction->type_action_id }}"><img src="{{ asset('assets/admin/images/icon/modifier.png') }}" width="20"></span>
                                <span class="btnSupprimerTypeAction" data-type_action_id="{{ $typeaction->type_action_id }}" title="Supprimer"><img src="{{ asset('assets/admin/images/icon/supprimer.png') }}" width="20"></span>
                            </td> 
                        </tr>
                    @endforeach   
                </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@foreach($typeactions as $typeaction)
    <div class="modal fade" id="EditTypeAction{{ $typeaction->type_action_id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modifier le type d'action</h4>
                </div>
                <form method="POST" action="{{ route('modifier_type_action', $typeaction->type_action_id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body"> 
                        <div class="form-group">
                            <label>Code du type d'action <span class="text-danger">*</span></label>
                            <input type="text" name="type_action_code" id="type_action_code" class="form-control" value="{{ $typeaction->type_action_code }}" required>
                        </div> 
                        <div class="form-group">
                            <label>Nom du type d'action <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="nom" class="form-control" value="{{ $typeaction->type_action_nom }}" required>
                        </div> 
                        <div class="form-group">
                            <label>Statut <span class="text-danger">*</span></label>
                            <select name="statut" id="statut" class="form-control" required>
                                <option value="">Choisir</option>
                                <option value="BROUILLON" {{ $typeaction->type_action_statut == 'BROUILLON' ? 'selected' : '' }}>BROUILLON</option>
                                <option value="VALIDE" {{ $typeaction->type_action_statut == 'VALIDE' ? 'selected' : '' }}>VALIDE</option>
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

<div class="modal fade" id="AjouterTypeAction" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Ajouter un nouveau type d'action</h4>
            </div>
            <form method="POST" action="{{ route('save_type_action') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Code du type d'action <span class="text-danger">*</span></label>
                        <input type="text" name="type_action_code" id="type_action_code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nom du type d'action <span class="text-danger">*</span></label>
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