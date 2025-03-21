@extends('layouts.app')
@section('title')
    {{ $ticket->ticket_code }}
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

</style>
<h3 class="page-title">
    {{ $ticket->ticket_code }}
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
         <a href="#">Détails : {{ $ticket->ticket_code }}</a>
      </li>
   </ul> 
   <div class="page-toolbar">
      <a href="{{ route('liste_ticket') }}">
         <div id="dashboard-report-range" class="tooltips btn btn-fit-height btn-sm green-haze btn-dashboard-daterange">
            <i class="icon-arrow-left"></i>
            Retour à la liste
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
                     <span class="badge badge-success">{{ $ticket->ticket_code }}</span>
                  </div>
               </div>  
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Type action : 
                  </div>
                  <div class="col-md-7 value">
                     <?php echo(html_entity_decode($ticket->type_action_nom)) ?>
                  </div>
               </div>
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date & Heure de fin : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateFromDB($ticket->ticket_datefin) }} à {{ $ticket->ticket_heurefin }}
                  </div>
               </div>
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Remarque : 
                  </div>
                  <div class="col-md-7 value">
                     <?php echo(html_entity_decode($ticket->ticket_remarque)) ?>
                  </div>
               </div>   
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Description : 
                  </div>
                  <div class="col-md-7 value">
                     <?php echo(html_entity_decode($ticket->ticket_description)) ?>
                  </div>
               </div>  
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date d'enregistrement : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateTimeFromDB($ticket->ticket_datecrea) }}
                  </div>
               </div>  
            </div>
            <div class="col-md-6">
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Site : 
                  </div>
                  <div class="col-md-7 value">
                     <?php echo(html_entity_decode($ticket->site_nom)) ?> - <?php echo(html_entity_decode($ticket->zone_nom)) ?>
                  </div>
               </div>  
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date & Heure de début : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateFromDB($ticket->ticket_datedebut) }} à {{ $ticket->ticket_heuredebut }}
                  </div>
               </div> 
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Tâches réalisées : 
                  </div>
                  <div class="col-md-7 value">
                     <?php echo(html_entity_decode($ticket->ticket_tacherealisee)) ?>
                  </div>
               </div> 
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date de déclaration : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateFromDB($ticket->ticket_datedeclaration) }}
                  </div>
               </div>   
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date de modification : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateTimeFromDB($ticket->ticket_datemodif) }}
                  </div>
               </div>  
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
