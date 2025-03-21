@extends('layouts.app')
@section('title')
    <?php echo(html_entity_decode($site->site_ihs)) ?> - <?php echo(html_entity_decode($site->site_nom)) ?>
@endsection
@section('content')
<h3 class="page-title">
    <?php echo(html_entity_decode($site->site_ihs)) ?> - <?php echo(html_entity_decode($site->site_nom)) ?>
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
         <a href="#">Détails : <?php echo(html_entity_decode($site->site_ihs)) ?></a>
      </li>
   </ul> 
   <div class="page-toolbar">
      <a href="{{ route('liste_site') }}">
         <div id="dashboard-report-range" class="tooltips btn btn-fit-height btn-sm green-haze btn-dashboard-daterange">
            <i class="icon-arrow-left"></i>
            Retour à la liste
         </div>
      </a>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        @include('includes.site')
        <div class="tabbable tabbable-custom tabbable-noborder">
            @include('includes.menu_site')
            <div class="tab-content">
                <div class="tab-pane @if(substr($_SERVER['REQUEST_URI'], 0,  38) == '/gestion-des-sites/nouveau-ticket-site') active @endif">
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <form  method="POST" action="{{ route('save_nouveau_ticket_site', $site->site_id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date de début <span class="text-danger">*</span></label>
                                                <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Heure de début <span class="text-danger">*</span></label>
                                                <input type="time" name="heure_debut" id="heure_debut" class="form-control" value="{{ old('heure_debut') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date de fin <span class="text-danger">*</span></label>
                                                <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ old('date_fin') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Heure de fin <span class="text-danger">*</span></label>
                                                <input type="time" name="heure_fin" id="heure_fin" class="form-control" value="{{ old('heure_fin') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Type d'action <span class="text-danger">*</span></label>
                                                <select name="type_action_id" id="type_action_id" class="form-control type_action_id" data-placeholder="Choisir" tabindex="1" required>
                                                    <option value="">Choisir</option>
                                                    @foreach ($typeactions as $typeaction)
                                                        <option value="{{ $typeaction->type_action_id }}" {{ old('type_action_id') == $typeaction->type_action_id ? 'selected' : '' }}>{{ $typeaction->type_action_nom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tâches réalisées <span class="text-danger">*</span></label>
                                                <input type="text" name="taches_realisees" id="taches_realisees" class="form-control" value="{{ old('taches_realisees') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Remarque</label>
                                                <input type="text" name="remarque" id="remarque" class="form-control" value="{{ old('remarque') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date de déclaration <span class="text-danger">*</span></label>
                                                <input type="date" name="date_declaration" id="date_declaration" class="form-control" value="<?php echo(gmdate('Y-m-d')) ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Description d'inventaire d'équipement passif</label>
                                                <textarea name="description" data-provide="markdown" rows="10" data-error-container="#editor_error">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="btn-set pull-right">
                                        <button type="submit" class="btn btn-success" id="formSubmit"><i class="fa fa-check-circle"></i> Enregistrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
