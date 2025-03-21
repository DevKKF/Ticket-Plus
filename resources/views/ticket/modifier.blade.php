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
                    @if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_003") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_006"))
                        <a class="btn btn-success btn-sm" href="{{ route('ajouter_ticket') }}"><i class="fa fa-plus-circle"></i> Nouveau site</a>
                        <a class="btn btn-success btn-sm" href="{{ route('liste_ticket') }}"><i class="icon-list"></i> Liste des tickets</a>
                    @endif
                </div>
            </div>
            <div class="portlet-body form">
                <form  method="POST" action="{{ route('modifier_ticket', $ticket->site_id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Site <span class="text-danger">*</span></label>
                                    <select name="site_id" id="site_id" class="form-control select_search">
                                        <option value="">Choisir</option>
                                        @foreach ($sites as $site)
                                            <option value="{{ $site->site_id }}" {{ $ticket->site_id == $site->site_id ? 'selected' : '' }}>{{ $site->site_ihs }} - {{ $site->site_nom }} - {{ $site->zone_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Techniciens <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" data-selected="{{ $ticket->user_id }}" class="form-control">
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
                                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ $ticket->ticket_datedebut }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Heure de début <span class="text-danger">*</span></label>
                                    <input type="time" name="heure_debut" id="heure_debut" class="form-control" value="{{ $ticket->ticket_heuredebut }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de fin <span class="text-danger">*</span></label>
                                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ $ticket->ticket_datefin }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Heure de fin <span class="text-danger">*</span></label>
                                    <input type="time" name="heure_fin" id="heure_fin" class="form-control" value="{{ $ticket->ticket_heurefin }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Type d'action <span class="text-danger">*</span></label>
                                    <select name="type_action_id" id="type_action_id" class="form-control type_action_id" data-placeholder="Choisir" tabindex="1" required>
                                        <option value="">Choisir</option>
                                        @foreach ($typeactions as $typeaction)
                                            <option value="{{ $typeaction->type_action_id }}" {{ $ticket->type_action_id == $typeaction->type_action_id ? 'selected' : '' }}>{{ $typeaction->type_action_nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tâches réalisées <span class="text-danger">*</span></label>
                                    <input type="text" name="taches_realisees" id="taches_realisees" class="form-control" value="{{ $ticket->ticket_tacherealisee }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Remarque</label>
                                    <input type="text" name="remarque" id="remarque" class="form-control" value="{{ $ticket->ticket_remarque }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date de déclaration <span class="text-danger">*</span></label>
                                    <input type="date" name="date_declaration" id="date_declaration" class="form-control" value="{{ $ticket->ticket_datedeclaration }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description d'inventaire d'équipement passif</label>
                                    <textarea name="description" data-provide="markdown" rows="10" data-error-container="#editor_error">{{ $ticket->ticket_description }}</textarea>
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
<script src="{{asset('assets/js/jquery.js') }}"></script>
@endsection
