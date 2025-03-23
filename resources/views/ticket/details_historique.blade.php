@extends('layouts.app')
@section('title')
    {{ $historique->historique_ticket_code }}
@endsection
@section('content')
<style>
   .note-white {
      background: #fff;
   }

   .border_right{
      border-right: 2px solid #F16623;
      height: auto;
   }

   textarea{
      background: #fff !important;
   }

</style>
<h3 class="page-title">
    {{ $historique->historique_ticket_code }}
</h3>
<div class="page-bar">
   <ul class="page-breadcrumb">
      <li>
         <i class="fa fa-home"></i>
         <a href="{{ route('home') }}">Home</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des tickets</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Détails historique du ticket : {{ $historique->historique_ticket_code }}</a>
      </li>
   </ul> 
   <div class="page-toolbar">
      <a href="{{ route('details_ticket',[$historique->ticket_id, Stdfn::clean_url(html_entity_decode($historique->historique_ticket_code))]) }}">
         <div id="dashboard-report-range" class="tooltips btn btn-fit-height btn-sm green-haze btn-dashboard-daterange">
            <i class="icon-arrow-left"></i>
            Retour au ticket
         </div>
      </a>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="note note-white note-bordered">
         <div class="row">
            <div class="col-md-6 border_right">
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Code : 
                  </div>
                  <div class="col-md-7 value">
                     <span class="badge badge-success">{{ $historique->historique_ticket_code }}</span>
                  </div>
               </div>  
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Type action : 
                  </div>
                  <div class="col-md-7 value">
                     {!! $historique->type_action_nom !!}
                  </div>
               </div>
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date & Heure de fin : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateFromDB($historique->historique_ticket_datefin) }} à {{ $historique->historique_ticket_heurefin }}
                  </div>
               </div> 
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date d'enregistrement : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateTimeFromDB($historique->historique_ticket_datecrea) }}
                  </div>
               </div> 
            </div>
            <div class="col-md-6">
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Site : 
                  </div>
                  <div class="col-md-7 value">
                     <?php echo(html_entity_decode($historique->site_nom)) ?> - <?php echo(html_entity_decode($historique->zone_nom)) ?>
                  </div>
               </div>  
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date & Heure de début : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateFromDB($historique->historique_ticket_datedebut) }} à {{ $historique->historique_ticket_heuredebut }}
                  </div>
               </div> 
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date de déclaration : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateFromDB($historique->historique_ticket_datedeclaration) }}
                  </div>
               </div>
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Enregistrer par : 
                  </div>
                  <div class="col-md-7 value f-bold">
                     {!! $historique->enregistrer_par->nom_prenoms !!}
                  </div>
               </div>    
            </div>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-12">
      <div class="note note-white note-bordered">
         <div class="row">
            <div class="col-md-12">
               <div class="form-group">
                  <label class="mb-3">Tâches réalisées : </label>
                  <textarea class="form-control" disabled>{!! $historique->historique_ticket_tacherealisee !!}</textarea>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                  <label class="mb-3">Remarques : </label>
                  <textarea class="form-control" disabled>{!! $historique->historique_ticket_remarque !!}</textarea>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                  <label class="mb-3">Description : </label>
                  <div style="background-color: white; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;">
                     {!! $historique->historique_ticket_description !!}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description', {
        readOnly: true,
        removePlugins: 'toolbar,elementspath,resize',
        height: '300px',
        contentsCss: [
            'body { background: #ffffff; margin: 10px; font-family: Arial, sans-serif; font-size: 14px; }',
        ]
    });
</script>
@endpush
@endsection
