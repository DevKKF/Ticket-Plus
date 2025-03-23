@extends('layouts.app')
@section('title')
    Gestion des actions tickets
@endsection
@section('content') 
<h3 class="page-title">
   Gestion des actions tickets
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
         <a href="#">Gestion des actions tickets</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-settings font-20"></i>Liste des actions tickets
            </div>
            <div class="actions">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#AjouterActionTicket"><i class="fa fa-plus-circle"></i> Nouvelle action ticket</button>
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
                    @foreach($actionticket as $action)
                        <tr>
                            <td class="d-none"></td>
                            <td>{{ $action->action_ticket_code }}</td>
                            <td>{{ $action->action_ticket_nom }}</td>
                            <td><span class="badge badge-<?php echo(str_replace(' ','', $action->action_ticket_statut)); ?>">{{ $action->action_ticket_statut }}</span></td>
                            <td>{{ Stdfn::dateFromDB($action->action_ticket_datecrea) }}</td>
                            <td class="text-center action_button">
                                <span data-toggle="modal" title="Modifier" data-target="#EditActionTicket{{ $action->action_ticket_id }}"><img src="{{ asset('assets/admin/images/icon/modifier.png') }}" width="20"></span>
                                <span class="btnSupprimerActionTicket" data-action_ticket_id="{{ $action->action_ticket_id }}" title="Supprimer"><img src="{{ asset('assets/admin/images/icon/supprimer.png') }}" width="20"></span>
                            </td> 
                        </tr>
                    @endforeach   
                </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

@foreach($actionticket as $action)
    <div class="modal fade" id="EditActionTicket{{ $action->action_ticket_id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Modifier l'action ticket</h4>
                </div>
                <form method="POST" action="{{ route('modifier_action_ticket', $action->action_ticket_id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body"> 
                        <div class="form-group">
                            <label>Code de l'action ticket <span class="text-danger">*</span></label>
                            <input type="text" name="action_ticket_code" id="action_ticket_code" class="form-control" value="{{ $action->action_ticket_code }}" required>
                        </div> 
                        <div class="form-group">
                            <label>Nom de l'action ticket <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="nom" class="form-control" value="{{ $action->action_ticket_nom }}" required>
                        </div> 
                        <div class="form-group">
                            <label>Statut <span class="text-danger">*</span></label>
                            <select name="statut" id="statut" class="form-control" required>
                                <option value="">Choisir</option>
                                <option value="BROUILLON" {{ $action->action_ticket_statut == 'BROUILLON' ? 'selected' : '' }}>BROUILLON</option>
                                <option value="VALIDE" {{ $action->action_ticket_statut == 'VALIDE' ? 'selected' : '' }}>VALIDE</option>
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

<div class="modal fade" id="AjouterActionTicket" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Ajouter une nouvelle action ticket</h4>
            </div>
            <form method="POST" action="{{ route('save_action_ticket') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Code de l'action ticket <span class="text-danger">*</span></label>
                        <input type="text" name="action_ticket_code" id="action_ticket_code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nom de l'action ticket <span class="text-danger">*</span></label>
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