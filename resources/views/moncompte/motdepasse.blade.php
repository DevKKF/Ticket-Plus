@extends('layouts.app')
@section('title')
    Mot de passe
@endsection
@section('content')
<h3 class="page-title">
    Mot de passe
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ route('home') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="#">Mon compte</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="#">Mot de passe</a>
        </li>
    </ul>
</div>
<div class="row">
   <div class="col-md-12">
        @include('includes.moncompte')
        <div class="profile-content">
         <div class="row">
            <div class="col-md-12">
               <div class="portlet light">
                  <div class="portlet-title tabbable-line" style="border-bottom: 2px solid #ccc !important;">
                     <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">Changer mon mot de passe</span>
                     </div>
                  </div>
                  <div class="portlet-body">
                     <div class="tab-content">
                        <form action="{{ route('save_changer_mot_passe') }}" method="POST" id="changer_mot_de_passe" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="conf_password" name="conf_password">
                            </div>
                            <div class="margin-top-10">
                                <button type="submit" class="btn green-haze" style="float:right !important">
                                Mot de passe </button>
                            </div><br><br>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
    $(document).ready(function() {
        // Validation des règles pour le formulaire d'église
        $('#changer_mot_de_passe').validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                },
                conf_password: {
                    required: true,
                    equalTo: '#password',
                },
            },
            messages: {
                password: {
                    required: "Le nouveau mot de passe est obligatoire.",
                    minlength: "Le nouveau mot de passe doit être d'au moins de 6 caractères.",
                },
                conf_password: {
                    required: "La confirmation du mot de passe obligatoire.",
                    equalTo: "La confirmation du mot de passe ne correspond pas au nouveau mot de passe.",
                },
            },
            errorElement: 'span', // Balise utilisée pour afficher les messages d'erreur
            errorPlacement: function(error, element) {
                // Placement des messages d'erreur dans la div 'error-msg'
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                // Ajoute la classe 'is-invalid' aux éléments en cas d'erreur
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                // Supprime la classe 'is-invalid' des éléments en cas de validation réussie
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection