@extends('layouts.app')
@section('title')
    Détails de la demande
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
    Détails de la demande
</h3>
<div class="page-bar">
   <ul class="page-breadcrumb">
      <li>
         <i class="fa fa-home"></i>
         <a href="{{ route('home') }}">Home</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Gestion des demandes</a>
         <i class="fa fa-angle-right"></i>
      </li>
      <li>
         <a href="#">Détails</a>
      </li>
   </ul> 
   <div class="page-toolbar">
      <a href="{{ route('liste_des_demandes') }}">
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
                    Type de demande : 
                  </div>
                  <div class="col-md-7 value">
                    @if($demande->action_ticket_code == "MODIFIER")
                        <span class="badge badge-success">{{ $demande->action_ticket_nom }}</span>
                    @elseif($demande->action_ticket_code == "SUPPRIMER")
                        <span class="badge badge-danger">{{ $demande->action_ticket_nom }}</span>
                    @else
                        <span class="badge badge-default">{{ $demande->action_ticket_nom }}</span>
                    @endif
                  </div>
               </div>  
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Date de la demande : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateFromDB($demande->demande_date) }}
                  </div>
               </div> 
               <div class="row static-info">
                  <div class="col-md-5 name">
                    Demande faite par : 
                  </div>
                  <div class="col-md-7 value f-bold">
                    @if($demande->enregistrer_par)
                        {!! $demande->enregistrer_par->nom_prenoms !!}
                    @endif
                  </div>
               </div>    
               <div class="row static-info">
                  <div class="col-md-5 name">
                    Consulter par : 
                  </div>
                  <div class="col-md-7 value f-bold">
                    @if($demande->consulter_par)
                        {!! $demande->consulter_par->nom_prenoms !!}
                    @endif
                  </div>
               </div> 
               @if($demande->valider_par or $demande->annuler_par)
               <div class="row static-info">
                  <div class="col-md-5 name">
                    @if($demande->valider_par)
                        Valider par : 
                    @endif

                    @if($demande->annuler_par)
                        Annuler par : 
                    @endif
                  </div>
                  <div class="col-md-7 value f-bold">
                    @if($demande->valider_par)
                        {!! $demande->valider_par->nom_prenoms !!}
                    @endif
                    @if($demande->annuler_par)
                        {!! $demande->annuler_par->nom_prenoms !!}
                    @endif
                  </div>
               </div> 
               @endif
            </div>
            <div class="col-md-6">
               <div class="row static-info">
                  <div class="col-md-5 name">
                    Demande à traiter : 
                  </div>
                  <div class="col-md-7 value f-bold">
                    {!! $demande->demande_a_traiter !!}
                  </div>
               </div>  
               <div class="row static-info">
                  <div class="col-md-5 name">
                    Date & heure de création : 
                  </div>
                  <div class="col-md-7 value f-bold">
                    {{ Stdfn::dateTimeFromDB($demande->demande_datecrea) }}
                  </div>
               </div>   
               <div class="row static-info">
                  <div class="col-md-5 name">
                    Statut : 
                  </div>
                  <div class="col-md-7 value f-bold">
                    <span class="badge badge-<?php echo(str_replace(' ','', $demande->demande_statut)); ?>">{{ $demande->demande_statut }}</span>
                  </div>
               </div> 
               <div class="row static-info">
                  <div class="col-md-5 name">
                    Date de consultation : 
                  </div>
                  <div class="col-md-7 value f-bold">
                    {{ Stdfn::dateFromDB($demande->demande_date_consulte) }}
                  </div>
               </div>  
               @if($demande->valider_par or $demande->annuler_par)
               <div class="row static-info">
                  <div class="col-md-5 name">
                    @if($demande->valider_par)
                        Date de validation : 
                    @endif

                    @if($demande->annuler_par)
                        Date d'annulation : 
                    @endif
                  </div>
                  <div class="col-md-7 value f-bold">
                    @if($demande->valider_par)
                        {{ Stdfn::dateTimeFromDB($demande->demande_date_valide) }}
                    @endif
                    @if($demande->annuler_par)
                        {{ Stdfn::dateTimeFromDB($demande->demande_date_annuelle) }}
                    @endif
                  </div>
               </div> 
               @endif
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
                  <label class="mb-3">Description de la demande : </label>
                  <textarea class="form-control" disabled>{!! $demande->demande_description !!}</textarea>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<h3 class="f-bold"><u>Détails du tickets : {{ $ticket->ticket_code }}</u></h3>

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
                     {!! $ticket->type_action_nom !!}
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
                     Date d'enregistrement : 
                  </div>
                  <div class="col-md-7 value">
                     {{ Stdfn::dateTimeFromDB($ticket->ticket_datecrea) }}
                  </div>
               </div> 
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Enregistrer par : 
                  </div>
                  <div class="col-md-7 value f-bold">
                     {!! $ticket->enregistrer_par->nom_prenoms !!}
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
               <div class="row static-info">
                  <div class="col-md-5 name">
                     Dernière modification par : 
                  </div>
                  <div class="col-md-7 value f-bold">
                     @if($ticket->modifier_par)
                        {!! $ticket->modifier_par->nom_prenoms !!}
                     @endif
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
                  <textarea class="form-control" disabled>{!! $ticket->ticket_tacherealisee !!}</textarea>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                  <label class="mb-3">Remarques : </label>
                  <textarea class="form-control" disabled>{!! $ticket->ticket_remarque !!}</textarea>
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group">
                  <label class="mb-3">Description : </label>
                  <div style="background-color: white; padding: 10px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;">
                     {!! $ticket->ticket_description !!}
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
               <h3 class="f-bold"><u>Historique des modifications</u></h3>
               <div class="table-scrollable">
                  <table class="table table-striped table-bordered table-hover" id="">
                     <thead>
                        <tr>
                           <th class="d-none"></th>
                           <th>Modification faite par</th>
                           <th>Type action</th>
                           <th>Date & Heure de début</th>
                           <th>Date & Heure de fin</th>
                           <th>Date de déclaration</th>
                           <th class="text-center">Détails</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($historiques as $historique)
                           <tr>
                              <td class="d-none"></td>
                              <td>{!! $historique->nom_prenoms !!}</td>
                              <td>{{ $historique->type_action_nom }}</td>
                              <td>{{ Stdfn::dateFromDB($historique->historique_ticket_datedebut) }} à {{ $historique->historique_ticket_heuredebut }}</td>
                              <td>{{ Stdfn::dateFromDB($historique->historique_ticket_datefin) }} à {{ $historique->historique_ticket_heurefin }}</td>
                              <td>{{ Stdfn::dateFromDB($historique->historique_ticket_datedeclaration) }}</td>
                              <td class="text-center">
                                 <a href="{{ route('details_historique_ticket', $historique->historique_ticket_id) }}"><img src="{{ asset('assets/admin/images/icon/details.png') }}" width="20"></a>
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
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
