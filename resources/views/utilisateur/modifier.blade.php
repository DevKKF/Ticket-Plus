@extends('layouts.app')
@section('title')
    Modifier l'utilisateur
@endsection
@section('content')
<style>
    .img-responsive{
        display: block !important;
        width: 298px !important;
        height: 288px !important;
    }
</style>
<h3 class="page-title">
   Modifier l'utilisateur : <?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?>
</h3>
<div class="page-bar">
   <ul class="page-breadcrumb">
      <li>
         <i class="fa fa-home"></i>
         <a href="{{ route('home') }}">Home</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des utilisateurs</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Modifier l'utilisateur</a>
      </li>
   </ul>
</div>
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="portlet box tiketplus">
         <div class="portlet-title">
            <div class="caption">
               <i class="icon-user-follow font-20"></i>Modifier l'utilisateur
            </div>
            <div class="actions">
                <a class="btn btn-success btn-sm" href="{{ route('ajouter_utilisateur') }}"><i class="fa fa-plus-circle"></i> Nouveau utilisateur</a>
                @if(in_array(Auth::user()->profil_id, [1, 2]))
                    <a class="btn btn-success btn-sm" href="{{ route('liste_utilisateur') }}"><i class="icon-users"></i> Liste des utilisateurs</a>
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ImporterUtilisateur"><i class="fa fa-users"></i> Importer des techniciens</button>
                @endif
            </div>
         </div>
         <div class="portlet-body form">
            @if(in_array(Auth::user()->profil_id, [1, 2]))
                <form  method="POST" action="{{ route('save_modifier_utilisateur', $utilisateur->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom et Prénoms <span class="text-danger">*</span></label>
                                    <input type="text" name="nom_prenoms" id="nom_prenoms" class="form-control" value="<?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" name="telephone" id="telephone" class="form-control" value="{{ $utilisateur->telephone }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Autre téléphone</label>
                                    <input type="text" name="autre_telephone" id="autre_telephone" class="form-control" value="{{ $utilisateur->autre_telephone }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Login <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $utilisateur->email }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mot de passe <span class="text text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" value="{{ old('password') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmer mot de passe <span class="text text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Photo</label>
                                    <input type="file" class="form-control" name="photo" value="{{ old('photo') }}" accept=".png, .jpg, .jpeg">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Niveau d'accès <span class="text-danger">*</span></label>
                                    <select name="profil_id" id="profil_id" class="form-control" onchange="getActionsModification(this.value, {{ $utilisateur->id ?? 'null' }})" required>
                                        <option value="">Choisir</option>
                                        @foreach($profils as $profil)
                                            <option value="{{ $profil->profil_id }}" {{ $utilisateur->profil_id == $profil->profil_id ? 'selected' : '' }}>{{ $profil->profil_nom }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group" style="background:#4D5B69; font-weight:bold;">
                                    <div id="actions"></div>
                                </div>
                            </div>
                            <div class="col-md-12 site_choise mt-5" style="display:none">
                                <div class="form-group">
                                    <label>Sites à gérer <span class="text-danger">*</span></label>
                                    <select name="site_ids[]" id="multi-value-select" multiple="multiple" class="select_multiple form-control" data-placeholder="Choisir un ou des site(s)" tabindex="1">
                                        <option value="">Choisir un ou des site(s)</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->site_id }}"
                                                @foreach($site_gerer as $site_ge)
                                                    {{ $site_ge->site_id == $site->site_id ? 'selected' : '' }}
                                                @endforeach 
                                            >{{ $site->site_nom }} - {{ $site->zone_nom }}</option>
                                        @endforeach 
                                    </select>
                                </div> 
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="form-actions right">
                        <button type="submit" class="btn btn-success" id="formSubmit"><i class="fa fa-check-circle"></i> Enregistrer</button>
                    </div>
                </form>        
            @endif    
         </div>
      </div>
   </div>
</div>

@include('utilisateur.modal_importer_utilisateur')

<script>
    $(document).ready(function() {
        let selectedProfil = $('#profil_id').val();
        let userId = {{ $utilisateur->id ?? 'null' }};
        if (selectedProfil) {
            getActionsModification(selectedProfil, userId);
        }
    });

    function getActionsModification(profil_id, user_id = null) {
        if (profil_id) {

            if(profil_id == 3){
                $('.site_choise').show();
            }else{
                $('.site_choise').hide();
            }

            let url = '/profil-modification/' + profil_id + '/actions';
            if (user_id) {
                url += '/' + user_id; // Ajout du user_id si on est en modification
            }

            $.ajax({
                url: url,
                success: function(response) {
                    var html = '';
                    for (var i = 0; i < response.actions.length; i++) {
                        if (i % 2 === 0) {
                            html += '<div class="row" style="padding:5px !important; color:#fff !important">';
                        }
                        
                        var checked = response.action_autorisees.includes(response.actions[i].action_id) ? 'checked' : '';

                        html += '<div class="col-md-6">' +
                            '<input type="checkbox" class="css-control-input" name="actions[]" value="' + response.actions[i].action_id + '" ' + checked + '> ' + 
                            response.actions[i].action_nom + 
                            '</div>';
                        
                        if (i % 2 === 1 || i === response.actions.length - 1) {
                            html += '</div>';
                        }
                    }
                    $('#actions').html(html);
                }
            });
        } else {
            $('#actions').html('');
        }
    }
</script>
@endsection