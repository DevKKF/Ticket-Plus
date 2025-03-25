@extends('layouts.app')
@section('title')
    Modifier le site
@endsection
@section('content')
<h3 class="page-title">
   Modifier le site : {{ $site->site_ihs }}
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
            <a href="javascript::void(0)">Modifier le site</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="portlet box tiketplus">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-globe font-20"></i>Modifier le site : {{ $site->site_ihs }}
                </div>
                <div class="actions">
                    @if(in_array(Auth::user()->profil_id, [1, 2]))
                        <a class="btn btn-success btn-sm" href="{{ route('ajouter_site') }}"><i class="fa fa-plus-circle"></i> Nouveau site</a>
                    @endif
                    <a class="btn btn-success btn-sm" href="{{ route('liste_site') }}"><i class="icon-list"></i> Liste des sites</a>
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ImporterSite"><i class="icon-globe"></i> Importer des sites</button>
                </div>
            </div>
            <div class="portlet-body form">
                @if(in_array(Auth::user()->profil_id, [1, 2]))
                    <form  method="POST" action="{{ route('save_modifier_site', $site->site_id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="site_id" value="{{ $site->site_id }}">
                        <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Code du site <span class="text-danger">*</span></label>
                                    <input type="text" name="site_ihs" id="site_ihs" class="form-control" value="{{ $site->site_ihs }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom du site <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $site->site_nom }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SBC <span class="text-danger">*</span></label>
                                    <select name="sbc" id="sbc" class="form-control" required>
                                        <option value="">Chosir un SBC</option>
                                        <option value="TBC" {{ $site->site_sbc == 'TBC' ? 'selected' : '' }}>TBC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom du MM</label>
                                    <input type="text" name="nom_mm" id="nom_mm" class="form-control" value="{{ $site->site_nom_mm }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom du RM</label>
                                    <input type="text" name="nom_rm" id="nom_rm" class="form-control" value="{{ $site->site_nom_rm }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Région <span class="text-danger">*</span></label>
                                    <select name="region_id" id="region_id" class="form-control region_id" data-placeholder="Choisir une région" tabindex="1" required>
                                        <option value="">Choisir une région</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->region_id }}" {{ $site->region_id == $region->region_id ? 'selected' : '' }}>{{ $region->region_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Zone <span class="text-danger">*</span></label>
                                    <select name="zone_id" id="zone_id" class="form-control zone_id" data-selected="{{ $site->zone_id }}" required>
                                        <option value="">Choisir</option>  
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Opérateur <span class="text-danger">*</span></label>
                                    <select name="operateur_id" id="operateur_id" class="form-control operateur_id" data-placeholder="Choisir un opérateur" tabindex="1" required>
                                        <option value="">Choisir un opérateur</option>
                                        @foreach ($operateurs as $operateur)
                                            <option value="{{ $operateur->operateur_id }}" {{ $site->operateur_id == $operateur->operateur_id ? 'selected' : '' }}>{{ $operateur->operateur_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Priorité IHS <span class="text-danger">*</span></label>
                                    <select name="priorite_ihs_id" id="priorite_ihs_id" class="form-control priorite_ihs_id" data-placeholder="Choisir une priorité IHS" tabindex="1" required>
                                        <option value="">Choisir une priorité IHS</option>
                                        @foreach ($prioriteihs as $priorite_ihs)
                                            <option value="{{ $priorite_ihs->priorite_ihs_id }}" {{ $site->priorite_ihs_id == $priorite_ihs->priorite_ihs_id ? 'selected' : '' }}>{{ $priorite_ihs->priorite_ihs_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Topologie / Typologie <span class="text-danger">*</span></label>
                                    <select name="topologie_typologie_id" id="topologie_typologie_id" class="form-control topologie_typologie_id" data-placeholder="Choisir une topologie / typologie" tabindex="1" required>
                                        <option value="">Choisir une topologie / typologie</option>
                                        @foreach ($topologietypologies as $topologie_typologie)
                                            <option value="{{ $topologie_typologie->topologie_typologie_id }}" {{ $site->topologie_typologie_id == $topologie_typologie->topologie_typologie_id ? 'selected' : '' }}>{{ $topologie_typologie->topologie_typologie_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Statut <span class="text-danger">*</span></label>
                                    <select name="statut" id="statut" class="form-control" required>
                                        <option value="">Chosir un statut</option>
                                        <option value="BROUILLON" {{ $site->site_statut == 'BROUILLON' ? 'selected' : '' }}>BROUILLON</option>
                                        <option value="VALIDE" {{ $site->site_statut == 'VALIDE' ? 'selected' : '' }}>VALIDE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de création <span class="text-danger">*</span></label>
                                    <input type="date" name="datecreation" id="datecreation" class="form-control" value="{{ $site->site_date_creation }}">
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
