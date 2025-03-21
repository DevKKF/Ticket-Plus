@extends('layouts.app')
@section('title')
    Nouveau ticket
@endsection
@section('content')
<h3 class="page-title">
   Ajouter un nouveau ticket
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ route('home') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li class="sous-menu">
            <a href="javascript::void(0)">Gestion des tickets</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li class="sous-menu">
            <a href="javascript::void(0)">Nouveau ticket</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="portlet box tiketplus">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-drawer font-20"></i>Ajouter un nouveau ticket
                </div>
                <div class="actions">
                    <a class="btn btn-success btn-sm" href="{{ route('liste_ticket') }}"><i class="icon-list"></i> Liste des tickets</a>
                </div>
            </div>
            <div class="portlet-body form">
                <form  method="POST" action="{{ route('save_ticket') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Site <span class="text-danger">*</span></label>
                                    <select name="site_id" id="site_id" class="form-control select_search">
                                        <option value="">Choisir</option>
                                        @foreach ($sites as $site)
                                            <option value="{{ $site->site_id }}" {{ old('site_id') == $site->site_id ? 'selected' : '' }}>{{ $site->site_ihs }} - {{ $site->site_nom }} - {{ $site->zone_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Techniciens <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Choisir</option>
                                    </select>
                                </div>
                            </div>
                            <div id="bloc_info_site" style="display:none">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Code du site</label>
                                        <input class="form-control code_site" id="code_site" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom du site</label>
                                        <input class="form-control nom_site" id="nom_site" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Opérateur du site</label>
                                        <input class="form-control operateur_site" id="operateur_site" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zone du site</label>
                                        <input class="form-control zone_site" id="zone_site" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="bloc_ticket" style="display:none">
                            <hr>
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
                                    <textarea name="description" id="description" rows="10">{{ old('description') }}</textarea>
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
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#description',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 300,
        language: 'fr_FR',
        menubar: false,
        branding: false,
        promotion: false,
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
</script>
@endpush
