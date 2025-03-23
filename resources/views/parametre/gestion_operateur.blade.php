@extends('layouts.app')
@section('title')
    Gestion des opérateurs
@endsection
@section('content') 
<h3 class="page-title">
   Gestion des opérateurs
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
         <a href="#">Gestion des opérateurs</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-settings font-20"></i>Liste des opérateurs
            </div>
            <div class="actions">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#AjouterOperateur"><i class="fa fa-plus-circle"></i> Nouveau opérateur</button>
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
                    @foreach($operateurs as $operateur)
                        <tr>
                            <td class="d-none"></td>
                            <td>{{ $operateur->operateur_nom }}</td>
                            <td><span class="badge badge-<?php echo(str_replace(' ','', $operateur->operateur_statut)); ?>">{{ $operateur->operateur_statut }}</span></td>
                            <td>{{ Stdfn::dateFromDB($operateur->operateur_datecrea) }}</td>
                            <td class="text-center action_button">
                                <span data-toggle="modal" title="Modifier" data-target="#EditOperateur{{ $operateur->operateur_id }}"><img src="{{ asset('assets/admin/images/icon/modifier.png') }}" width="20"></span>
                                <span class="btnSupprimerOperateur" data-operateur_id="{{ $operateur->operateur_id }}" title="Supprimer"><img src="{{ asset('assets/admin/images/icon/supprimer.png') }}" width="20"></span>
                            </td> 
                        </tr>
                    @endforeach   
                </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@foreach($operateurs as $operateur)
    <div class="modal fade" id="EditOperateur{{ $operateur->operateur_id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modifier l'opérateur</h4>
                </div>
                <form method="POST" action="{{ route('modifier_operateur', $operateur->operateur_id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body"> 
                        <div class="form-group">
                            <label>Nom de l'opérateur <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="nom" class="form-control" value="{{ $operateur->operateur_nom }}" required>
                        </div> 
                        <div class="form-group">
                            <label>Statut <span class="text-danger">*</span></label>
                            <select name="statut" id="statut" class="form-control" required>
                                <option value="">Choisir</option>
                                <option value="BROUILLON" {{ $operateur->operateur_statut == 'BROUILLON' ? 'selected' : '' }}>BROUILLON</option>
                                <option value="VALIDE" {{ $operateur->operateur_statut == 'VALIDE' ? 'selected' : '' }}>VALIDE</option>
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

<div class="modal fade" id="AjouterOperateur" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Ajouter un nouveau opérateur</h4>
            </div>
            <form method="POST" action="{{ route('save_operateur') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom de l'opérateur <span class="text-danger">*</span></label>
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