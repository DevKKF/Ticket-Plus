@extends('layouts.app')
@section('title')
    Nouveau site
@endsection
@section('content')
<h3 class="page-title">
   Ajouter un nouveau site
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ route('home') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li class="sous-menu">
            <a href="javascript::void(0)">Gestion des sites</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li class="sous-menu">
            <a href="javascript::void(0)">Nouveau site</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="portlet box tiketplus">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-globe font-20"></i>Ajouter un nouveau site
                </div>
                <div class="actions">
                    <a class="btn btn-success btn-sm" href="{{ route('liste_site') }}"><i class="icon-list"></i> Liste des sites</a>
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ImporterSite"><i class="icon-globe"></i> Importer des sites</button>
                </div>
            </div>
            <div class="portlet-body form">
                @if(in_array(Auth::user()->profil_id, [1, 2]))
                    <form  method="POST" action="{{ route('save_site') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Code du site <span class="text-danger">*</span></label>
                                        <input type="text" name="site_ihs" id="site_ihs" class="form-control" value="{{ old('site_ihs') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom du site <span class="text-danger">*</span></label>
                                        <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>SBC <span class="text-danger">*</span></label>
                                        <select name="sbc" id="sbc" class="form-control" required>
                                            <option value="">Chosir un SBC</option>
                                            <option value="TBC" {{ old('sbc') == 'TBC' ? 'selected' : '' }}>TBC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom du MM</label>
                                        <input type="text" name="nom_mm" id="nom_mm" class="form-control" value="{{ old('nom_mm') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom du RM</label>
                                        <input type="text" name="nom_rm" id="nom_rm" class="form-control" value="{{ old('nom_rm') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Région <span class="text-danger">*</span></label>
                                        <select name="region_id" id="region_id" class="form-control region_id" data-placeholder="Choisir" tabindex="1" required>
                                            <option value="">Choisir</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->region_id }}" {{ old('region_id') == $region->region_id ? 'selected' : '' }}>{{ $region->region_nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zone <span class="text-danger">*</span></label>
                                        <select name="zone_id" id="zone_id" class="form-control" required>
                                            <option value="">Choisir</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Opérateur <span class="text-danger">*</span></label>
                                        <select name="operateur_id" id="operateur_id" class="form-control operateur_id" data-placeholder="Choisir" tabindex="1" required>
                                            <option value="">Choisir</option>
                                            @foreach ($operateurs as $operateur)
                                                <option value="{{ $operateur->operateur_id }}" {{ old('operateur_id') == $operateur->operateur_id ? 'selected' : '' }}>{{ $operateur->operateur_nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Priorité IHS <span class="text-danger">*</span></label>
                                        <select name="priorite_ihs_id" id="priorite_ihs_id" class="form-control priorite_ihs_id" data-placeholder="Choisir" tabindex="1" required>
                                            <option value="">Choisir</option>
                                            @foreach ($prioriteihs as $priorite_ihs)
                                                <option value="{{ $priorite_ihs->priorite_ihs_id }}" {{ old('priorite_ihs_id') == $priorite_ihs->priorite_ihs_id ? 'selected' : '' }}>{{ $priorite_ihs->priorite_ihs_nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Topologie / Typologie <span class="text-danger">*</span></label>
                                        <select name="topologie_typologie_id" id="topologie_typologie_id" class="form-control topologie_typologie_id" data-placeholder="Choisir" tabindex="1" required>
                                            <option value="">Choisir</option>
                                            @foreach ($topologietypologies as $topologie_typologie)
                                                <option value="{{ $topologie_typologie->topologie_typologie_id }}" {{ old('topologie_typologie_id') == $topologie_typologie->topologie_typologie_id ? 'selected' : '' }}>{{ $topologie_typologie->topologie_typologie_nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Statut <span class="text-danger">*</span></label>
                                        <select name="statut" id="statut" class="form-control" required>
                                            <option value="">Chosir un statut</option>
                                            <option value="BROUILLON" {{ old('statut') == 'BROUILLON' ? 'selected' : '' }}>BROUILLON</option>
                                            <option value="VALIDE" {{ old('statut') == 'VALIDE' ? 'selected' : '' }}>VALIDE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date de création <span class="text-danger">*</span></label>
                                        <input type="date" name="datecreation" id="datecreation" class="form-control" value="{{ old('datecreation') }}">
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
                @endif
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/js/jquery.js') }}"></script>
@include('site.modal_importer_site')
@endsection
