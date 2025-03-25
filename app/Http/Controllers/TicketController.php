<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Region;
use App\Models\TypeAction;
use App\Models\Zone;
use App\Models\Operateur;
use App\Models\PrioriteIHS;
use App\Models\TopologieTypologie;
use App\Models\Site;
use App\Models\SiteUser;
use App\Models\Ticket;
use App\Models\ActionTicket;
use App\Models\HistoriqueTicket;
use App\Models\Demande;

use App\Exports\TicketExport;

use Stdfn;
use View;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;

class TicketController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }  


    //Ajouter un ticket
    public function AjouterTicket()
	{
        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002")){
            $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                        ->where(['site_statut'=>"VALIDE"])
                        ->orderby('site_nom','ASC')
                        ->get();
        }else{

            $sites = SiteUser::leftjoin('site', 'site.site_id', 'site_user.site_id')
                            ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                            ->where(['site_statut'=>"VALIDE", 'site_user.user_id'=>Auth::user()->id])
                            ->get();
        }
		$typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();

		return view('ticket.ajouter', [
            'sites' => $sites, 
            'typeactions' => $typeactions, 
        ]);
	}

    //Save site
    public function SaveTicket(Request $request){

        $validator = Validator::make($request->all(), [
            'date_declaration' => 'required|date',
            'remarque' => 'required',
            'taches_realisees' => 'required',
            'type_action_id' => 'required',
            'heure_fin' => 'required',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'heure_debut' => 'required',
            'date_debut' => 'required|date',
            'user_id' => 'required',
            'site_id' => 'required',
        ], [
            'date_declaration.date' => "La date de déclaration doit être au format AAAA/JJ/MM.",
            'date_declaration.required' => "La date de déclaration est obligatoire.",            
            'taches_realisees.required' => "Les tâches réalisées sont obligatoire.",
            'type_action_id.required' => "Le type d'action est obligatoire.",
            'heure_fin.required' => "L'heure de fin est obligatoire.",
            'date_fin.after_or_equal' => "La date de fin doit être postérieure ou égale à la date de début.",
            'date_fin.date' => "La date de fin doit être au format AAAA/JJ/MM.",
            'date_fin.required' => "La date de fin est obligatoire.",
            'heure_debut.required' => "L'heure de début est obligatoire.",
            'date_debut.date' => "La date de début doit être au format AAAA/JJ/MM.",
            'date_debut.required' => "La date de début est obligatoire.",
            'user_id.required' => "Le technicien est obligatoire.",
            'site_id.required' => "Le site est obligatoire.",
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $site_rec = Site::find($request->site_id);
        $type_action_rec = TypeAction::find($request->type_action_id);

        //Préfixe du code du ticket
        $prefixe = "";

        if($type_action_rec->type_action_code == "PMEX"){
            $prefixe = "PM";
        }elseif($type_action_rec->type_action_code == "MC"){
            $prefixe = "CM";
        }else{
            $prefixe = $type_action_rec->type_action_code;
        }

        // Générer le code du ticket
        $code_ticket = Stdfn::genererCodeTicket($site_rec->site_sbc, $prefixe);

        $date_delaismodification = Carbon::parse($request->date_declaration)->addDays(2);

        $ticket = new Ticket();
 
        $ticket->user_id                 = $request->user_id;
        $ticket->creerpar_id             = Auth::id();
        $ticket->site_id                 = $request->site_id;
        $ticket->type_action_id          = $request->type_action_id;
        $ticket->ticket_code             = $code_ticket;
        $ticket->ticket_datedebut        = htmlspecialchars($request->date_debut);
        $ticket->ticket_heuredebut       = htmlspecialchars($request->heure_debut);
        $ticket->ticket_datefin          = htmlspecialchars($request->date_fin);
        $ticket->ticket_heurefin         = htmlspecialchars($request->heure_fin);
        $ticket->ticket_tacherealisee    = htmlspecialchars($request->taches_realisees);
        $ticket->ticket_remarque         = htmlspecialchars($request->remarque);
        $ticket->ticket_description      = htmlspecialchars($request->description);
        $ticket->ticket_datedeclaration  = htmlspecialchars($request->date_declaration);
        $ticket->ticket_datedelaismodif  = $date_delaismodification;
        $ticket->ticket_datecrea         = gmdate('Y-m-d H:i:s');
        $ticket->save();

        return back()->with('success',"Ticket enregsitré avec succès !");
    }

    //Liste des tickets
    public function ListeTicket(Request $request)
	{

        $code = $request->query('c', '');
        $site = $request->query('s', '');
        $typeaction = $request->query('t', '');
        $datedeclaration = $request->query('d', '');

        $whereRaw = ' 1 ';

        if (!empty($code) || !empty($site) || !empty($typeaction) || !empty($datedeclaration)){
            if(!empty($code)){
                $whereRaw .= ' AND ticket.ticket_code LIKE "%' . $code . '%"';
            }

            if(!empty($site)){
                $whereRaw .= ' AND ticket.site_id = "' . $site . '"';
            }

            if(!empty($typeaction)){
                $whereRaw .= ' AND ticket.type_action_id = "' . $typeaction . '"';
            }

            if (!empty($datedeclaration)) {
                $whereRaw .= ' AND ticket.ticket_datedeclaration = "' . $datedeclaration . '"';
            }
        }

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002")){
            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                            ->leftjoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                            ->whereRaw($whereRaw)
                            ->orderby('ticket_id','DESC')
                            ->get();
        }else{
            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                    ->leftjoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                    ->whereRaw($whereRaw)
                    ->where(['ticket.user_id'=>Auth::id()])
                    ->orderby('ticket_id','DESC')
                    ->get();
        }
		
        $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')->where(['site_statut'=>"VALIDE"])->orderby('site_nom','ASC')->get();
		$typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();
        $actionticket = ActionTicket::where(['action_ticket_statut'=>"VALIDE"])->orderby('action_ticket_id','ASC')->get();

        // Stocker les données de recherche en session
        Session::put('recherche_data_ticket', [
            'code' => $code,
            'site' => $site,
            'typeaction' => $typeaction,
            'datedeclaration' => $datedeclaration,
        ]);

		return view('ticket.liste', [
            'tickets' => $tickets,  
            'sites' => $sites, 
            'typeactions' => $typeactions, 
            'selected_code'=>$code,
            'selected_site'=>$site,
            'selected_typeaction'=>$typeaction,
            'actionticket' => $actionticket,
            'selected_datedeclaration'=>$datedeclaration,
        ]);
	}
 
    //Détails un ticket
    public function DetailsTicket(Request $request, $ticket_id)
    {
        $ticket = Ticket::with(['enregistrer_par', 'modifier_par'])
                        ->leftJoin('site', 'site.site_id', 'ticket.site_id')
                        ->leftJoin('zone', 'zone.zone_id', 'site.zone_id')
                        ->leftJoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                        ->leftJoin('users as createur', 'createur.id', 'ticket.creerpar_id')
                        ->leftJoin('users as modificateur', 'modificateur.id', 'ticket.modifierpar_id')
                        ->select('ticket.*', 'site.site_nom', 'zone.zone_nom', 'type_action.type_action_nom',
                                'createur.nom_prenoms as createur_nom',
                                'modificateur.nom_prenoms as modificateur_nom')
                        ->where('ticket.ticket_id', $ticket_id)
                        ->first();

        if (!empty($ticket)) {

            $historiques = HistoriqueTicket::leftJoin('site', 'site.site_id', 'historique_ticket.site_id')
                                        ->leftJoin('zone', 'zone.zone_id', 'site.zone_id')
                                        ->leftJoin('users', 'users.id', 'historique_ticket.user_id')
                                        ->leftJoin('type_action', 'type_action.type_action_id', 'historique_ticket.type_action_id')
                                        ->where('historique_ticket.ticket_id', $ticket_id)
                                        ->get();

            return view('ticket.details', [
                'ticket' => $ticket,
                'historiques' => $historiques,
            ]);
        } else {
            return back()->with('warning', 'Ticket non trouvé !');
        }
    }

    //Détails ticket historique
    public function HistoriqueDetailsTicket(Request $request, $historique_ticket_id)
    {
        $historique = HistoriqueTicket::with(['enregistrer_par'])
                                    ->leftJoin('site', 'site.site_id', 'historique_ticket.site_id')
                                    ->leftJoin('zone', 'zone.zone_id', 'site.zone_id')
                                    ->leftJoin('type_action', 'type_action.type_action_id', 'historique_ticket.type_action_id')
                                    ->leftJoin('users as createur', 'createur.id', 'historique_ticket.user_id')
                                    ->select('historique_ticket.*', 'site.site_nom', 'zone.zone_nom', 'type_action.type_action_nom',
                                            'createur.nom_prenoms as createur_nom')
                                    ->where('historique_ticket.historique_ticket_id', $historique_ticket_id)
                                    ->first();

        if (!empty($historique)) {
            return view('ticket.details_historique', [
                'historique' => $historique,
            ]);
        } else {
            return back()->with('warning', 'Ticket non trouvé !');
        }
    }
 
    //Modifier un ticket
    public function ModifierTicket(Request $request, $ticket_id)
	{
        
        $ticket = Ticket::find($ticket_id);

		if (!empty($ticket)) {

            $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')->where(['site_statut'=>"VALIDE"])->orderby('site_nom','ASC')->get();
            $typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();

            $inventoryDescription = $ticket->ticket_description;

            return view('ticket.modifier', [
                'ticket' => $ticket, 
                'sites' => $sites, 
                'typeactions' => $typeactions, 
                'inventoryDescription' => $inventoryDescription, 
            ]);

        } else {
            return back()->with('warning', 'Ticket non trouvé !');
        }
	}

    public function SaveModifierTicket(Request $request, $ticket_id)
	{
        
        $ticket = Ticket::find($ticket_id);

		if (!empty($ticket)) {

            $validator = Validator::make($request->all(), [
                'date_declaration' => 'required|date',
                'remarque' => 'required',
                'taches_realisees' => 'required',
                'type_action_id' => 'required',
                'heure_fin' => 'required',
                'date_fin' => 'required|date|after_or_equal:date_debut',
                'heure_debut' => 'required',
                'date_debut' => 'required|date',
                'user_id' => 'required',
                'site_id' => 'required',
            ], [
                'date_declaration.date' => "La date de déclaration doit être au format AAAA/JJ/MM.",
                'date_declaration.required' => "La date de déclaration est obligatoire.",            
                'taches_realisees.required' => "Les tâches réalisées sont obligatoire.",
                'type_action_id.required' => "Le type d'action est obligatoire.",
                'heure_fin.required' => "L'heure de fin est obligatoire.",
                'date_fin.after_or_equal' => "La date de fin doit être postérieure ou égale à la date de début.",
                'date_fin.date' => "La date de fin doit être au format AAAA/JJ/MM.",
                'date_fin.required' => "La date de fin est obligatoire.",
                'heure_debut.required' => "L'heure de début est obligatoire.",
                'date_debut.date' => "La date de début doit être au format AAAA/JJ/MM.",
                'date_debut.required' => "La date de début est obligatoire.",
                'user_id.required' => "Le technicien est obligatoire.",
                'site_id.required' => "Le site est obligatoire.",
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            //Créer l'historique
            $historique_ticket = new HistoriqueTicket();

            $historique_ticket->ticket_id                          = $ticket->ticket_id;
            $historique->user_id                                   = Auth::id();
            $historique_ticket->creerpar_id                        = $ticket->creerpar_id;
            $historique_ticket->modifierpar_id                     = $ticket->modifierpar_id;
            $historique_ticket->site_id                            = $ticket->site_id;
            $historique_ticket->type_action_id                     = $ticket->type_action_id;
            $historique_ticket->historique_ticket_code             = $ticket->ticket_code;
            $historique_ticket->historique_ticket_datedebut        = $ticket->ticket_datedebut;
            $historique_ticket->historique_ticket_heuredebut       = $ticket->ticket_heuredebut;
            $historique_ticket->historique_ticket_datefin          = $ticket->ticket_datefin;
            $historique_ticket->historique_ticket_heurefin         = $ticket->ticket_heurefin;
            $historique_ticket->historique_ticket_tacherealisee    = $ticket->ticket_tacherealisee;
            $historique_ticket->historique_ticket_remarque         = $ticket->ticket_remarque;
            $historique_ticket->historique_ticket_description      = $ticket->ticket_description;
            $historique_ticket->historique_ticket_datedeclaration  = $ticket->ticket_datedeclaration;
            $historique_ticket->historique_ticket_datedelaismodif  = $ticket->ticket_datedelaismodif;
            $historique_ticket->historique_ticket_datecrea         = $ticket->ticket_datecrea;
            $historique_ticket->save();
            
            //Mis à jour de ticket
            $ticket->user_id                 = $request->user_id;
            $ticket->modifierpar_id          = Auth::id();
            $ticket->site_id                 = $request->site_id;
            $ticket->type_action_id          = $request->type_action_id;
            $ticket->ticket_datedebut        = htmlspecialchars($request->date_debut);
            $ticket->ticket_heuredebut       = htmlspecialchars($request->heure_debut);
            $ticket->ticket_datefin          = htmlspecialchars($request->date_fin);
            $ticket->ticket_heurefin         = htmlspecialchars($request->heure_fin);
            $ticket->ticket_tacherealisee    = htmlspecialchars($request->taches_realisees);
            $ticket->ticket_remarque         = htmlspecialchars($request->remarque);
            $ticket->ticket_description      = htmlspecialchars($request->description);
            $ticket->ticket_datedeclaration  = htmlspecialchars($request->date_declaration);
            $ticket->ticket_datemodif        = gmdate('Y-m-d H:i:s');
            $ticket->save();
    
            return redirect()->route('liste_ticket')->with('success',"Ticket modifié avec succès !");

        } else {
            return back()->with('warning', 'Ticket non trouvé !');
        }
	}

    //Save demande de suppression ou modifiction de ticket
    public function SaveDemandeActionTicket(Request $request, $ticket_id)
	{
        
        $ticket = Ticket::find($ticket_id);

		if (!empty($ticket)) {

            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'demande_a_traiter' => 'required',
                'demande_date' => 'required|date',
                'action_ticket_id' => 'required',
            ], [            
                'description.required' => "La description de la demande est obligatoire.",
                'demande_a_traiter.required' => "La demande à traiter est obligatoire.",
                'demande_date.date' => "La date de la demande doit être au format AAAA/JJ/MM.",
                'demande_date.required' => "La date de la demande est obligatoire.",
                'action_ticket_id.required' => "Le type d'action est obligatoire.",
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Récupération de la date du formulaire
            $dateDemande = Carbon::parse($request->input('demande_date'));

            // Ajout de 3 jours
            $datePlusTroisJours = $dateDemande->addDays(3);

            //Créer demande
            $demande = new Demande();
            
            $demande->user_id               = Auth::id();
            $demande->creerpar_id           = Auth::id();
            $demande->ticket_id             = $ticket->ticket_id;
            $demande->action_ticket_id      = $request->action_ticket_id;
            $demande->demande_date_delais   = $datePlusTroisJours;
            $demande->demande_date          = htmlspecialchars($request->demande_date);
            $demande->demande_a_traiter     = htmlspecialchars($request->demande_a_traiter);
            $demande->demande_description   = htmlspecialchars($request->description);
            $demande->demande_datecrea      = gmdate('Y-m-d H:i:s');
            $demande->demande_statut        = "EN COURS";
            $demande->save();
    
            return back()->with('success',"Demande enregistrée avec succès !");

        } else {
            return back()->with('warning', 'Ticket non trouvé !');
        }
	}

    //Liste des tickets PM
    public function TicketPM(Request $request)
    {
        $code = $request->query('c', '');
        $site = $request->query('s', '');
        $typeaction = $request->query('t', '');
        $datedeclaration = $request->query('d', '');

        $whereRaw = ' 1 ';

        if (!empty($code) || !empty($site) || !empty($typeaction) || !empty($datedeclaration)){
            if(!empty($code)){
                $whereRaw .= ' AND ticket.ticket_code LIKE "%' . $code . '%"';
            }

            if(!empty($site)){
                $whereRaw .= ' AND ticket.site_id = "' . $site . '"';
            }

            if(!empty($typeaction)){
                $whereRaw .= ' AND ticket.type_action_id = "' . $typeaction . '"';
            }

            if (!empty($datedeclaration)) {
                $whereRaw .= ' AND ticket.ticket_datedeclaration = "' . $datedeclaration . '"';
            }
        }

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002")){
            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->whereBetween('type_action.type_action_code',['PM','PMEX'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->orderby('ticket_id','DESC')
                                ->get();

        }else{
            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->where(['ticket.user_id'=>Auth::user()->id])
                                ->whereBetween('type_action.type_action_code',['PM','PMEX'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->get();
                    
        }

        $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')->where(['site_statut'=>"VALIDE"])->orderby('site_nom','ASC')->get();
		$typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();
        $actionticket = ActionTicket::where(['action_ticket_statut'=>"VALIDE"])->orderby('action_ticket_id','ASC')->get();

        // Stocker les données de recherche en session
        Session::put('recherche_data_ticket', [
            'code' => $code,
            'site' => $site,
            'typeaction' => $typeaction,
            'datedeclaration' => $datedeclaration,
        ]);

        return view('ticket.ticket_pm',[ 
            'tickets' => $tickets, 
            'sites' => $sites, 
            'typeactions' => $typeactions, 
            'actionticket' => $actionticket,
            'selected_code'=>$code,
            'selected_site'=>$site,
            'selected_typeaction'=>$typeaction,
            'selected_datedeclaration'=>$datedeclaration,  
        ]);
    }

    //Liste des tickets CM
    public function TicketCM(Request $request)
    {

        $code = $request->query('c', '');
        $site = $request->query('s', '');
        $typeaction = $request->query('t', '');
        $datedeclaration = $request->query('d', '');

        $whereRaw = ' 1 ';

        if (!empty($code) || !empty($site) || !empty($typeaction) || !empty($datedeclaration)){
            if(!empty($code)){
                $whereRaw .= ' AND ticket.ticket_code LIKE "%' . $code . '%"';
            }

            if(!empty($site)){
                $whereRaw .= ' AND ticket.site_id = "' . $site . '"';
            }

            if(!empty($typeaction)){
                $whereRaw .= ' AND ticket.type_action_id = "' . $typeaction . '"';
            }

            if (!empty($datedeclaration)) {
                $whereRaw .= ' AND ticket.ticket_datedeclaration = "' . $datedeclaration . '"';
            }
        }

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002")){
            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->whereBetween('type_action.type_action_code',['CM','MC'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->orderby('ticket_id','DESC')
                                ->get();

        }else{

            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->where(['ticket.user_id'=>Auth::user()->id])
                                ->whereBetween('type_action.type_action_code',['CM','MC'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->orderby('ticket_id','DESC')
                                ->get();
                    
        }

        $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')->where(['site_statut'=>"VALIDE"])->orderby('site_nom','ASC')->get();
		$typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();
        $actionticket = ActionTicket::where(['action_ticket_statut'=>"VALIDE"])->orderby('action_ticket_id','ASC')->get();

        // Stocker les données de recherche en session
        Session::put('recherche_data_ticket', [
            'code' => $code,
            'site' => $site,
            'typeaction' => $typeaction,
            'datedeclaration' => $datedeclaration,
        ]);

        return view('ticket.ticket_cm',[
            'tickets' => $tickets, 
            'sites' => $sites, 
            'typeactions' => $typeactions, 
            'actionticket' => $actionticket,
            'selected_code'=>$code,
            'selected_site'=>$site,
            'selected_typeaction'=>$typeaction,
            'selected_datedeclaration'=>$datedeclaration,   
        ]);
    }

    //Liste des autres tickets
    public function AutresTickets(Request $request)
    {

        $code = $request->query('c', '');
        $site = $request->query('s', '');
        $typeaction = $request->query('t', '');
        $datedeclaration = $request->query('d', '');

        $whereRaw = ' 1 ';

        if (!empty($code) || !empty($site) || !empty($typeaction) || !empty($datedeclaration)){
            if(!empty($code)){
                $whereRaw .= ' AND ticket.ticket_code LIKE "%' . $code . '%"';
            }

            if(!empty($site)){
                $whereRaw .= ' AND ticket.site_id = "' . $site . '"';
            }

            if(!empty($typeaction)){
                $whereRaw .= ' AND ticket.type_action_id = "' . $typeaction . '"';
            }

            if (!empty($datedeclaration)) {
                $whereRaw .= ' AND ticket.ticket_datedeclaration = "' . $datedeclaration . '"';
            }
        }

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002")){
            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->whereNotIn('type_action.type_action_code',['CM','MC','PM','PMEX'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->orderby('ticket_id','DESC')
                                ->get();

        }else{

            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->where(['ticket.user_id'=>Auth::user()->id])
                                ->whereNotIn('type_action.type_action_code',['CM','MC','PM','PMEX'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->orderby('ticket_id','DESC')
                                ->get();
                    
        }

        $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')->where(['site_statut'=>"VALIDE"])->orderby('site_nom','ASC')->get();
		$typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();
        $actionticket = ActionTicket::where(['action_ticket_statut'=>"VALIDE"])->orderby('action_ticket_id','ASC')->get();

        // Stocker les données de recherche en session
        Session::put('recherche_data_ticket', [
            'code' => $code,
            'site' => $site,
            'typeaction' => $typeaction,
            'datedeclaration' => $datedeclaration,
        ]);
        
        return view('ticket.autres_tickets',[
            'tickets' => $tickets, 
            'sites' => $sites, 
            'typeactions' => $typeactions, 
            'actionticket' => $actionticket,
            'selected_code'=>$code,
            'selected_site'=>$site,
            'selected_typeaction'=>$typeaction,
            'selected_datedeclaration'=>$datedeclaration,   
        ]);
    }

        //Supprimer ticket
	public function SupprimerTicket(Request $request)
	{

		$ticket_id = $request->ticket_id;

		$ticket = Ticket::find($ticket_id);

		if (!empty($ticket)) {

			$ticket->delete();

			echo 1;
		} else {
			echo 0;
		}
	}

    public function exporterCSV()
    {
        // Récupère les données de session ou un tableau vide
        $data = Session::get('recherche_data_ticket', []); 

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_003") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_006")){

            $query = Ticket::select('ticket_code', 'site_ihs', 'site_nom', 'site_sbc', 'nom_prenoms', 'telephone', 'autre_telephone', 'ticket_datedebut', 'ticket_heuredebut', 'type_action_nom', 'ticket_tacherealisee', 'ticket_remarque', 'ticket_datefin', 'ticket_heurefin')
                            ->leftjoin('site', 'site.site_id', '=', 'ticket.site_id')
                            ->leftjoin('users', 'users.id', '=', 'ticket.user_id')
                            ->leftjoin('type_action', 'type_action.type_action_id', '=', 'ticket.type_action_id')
                            ->orderBy('ticket_code', 'DESC');

            // Appliquer les filtres seulement si des valeurs existent
            if (!empty($data)) {
                if (!empty($data['code'])) {
                    $query->where('ticket.ticket_code', '=', $data['code']);
                }
                if (!empty($data['site'])) {
                    $query->where('site.site_id', '=', $data['site']);
                }
                if (!empty($data['typeaction'])) {
                    $query->where('type_action.type_action_id', '=', $data['typeaction']);
                }
                if (!empty($data['datedeclaration'])) {
                    $query->where('ticket.site_date_creation', '=', $data['datedeclaration']);
                }
            }

            $tickets = $query->get();

            if($tickets->count() > 0){
                return Excel::download(new TicketExport($tickets), 'liste-des-tickets.csv', \Maatwebsite\Excel\Excel::CSV);
            }else{
                return back()->with('info_warning',"Il n'y a pas de données à exporter de la base de données");
            }

        }else{
            return back()->with('info_warning',"Vous n'êtes pas autorisé faire de exportation");
        }
    }

    public function exporterExcel()
    {
        //Récupère les données de session ou un tableau vide
        $data = Session::get('recherche_data_ticket', []); 

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_003") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_006")){

            $query = Ticket::select('ticket_code', 'site_ihs', 'site_nom', 'site_sbc', 'nom_prenoms', 'telephone', 'autre_telephone', 'ticket_datedebut', 'ticket_heuredebut', 'type_action_nom', 'ticket_tacherealisee', 'ticket_remarque', 'ticket_datefin', 'ticket_heurefin')
                            ->leftjoin('site', 'site.site_id', '=', 'ticket.site_id')
                            ->leftjoin('users', 'users.id', '=', 'ticket.user_id')
                            ->leftjoin('type_action', 'type_action.type_action_id', '=', 'ticket.type_action_id')
                            ->orderBy('ticket_code', 'DESC');

            // Appliquer les filtres seulement si des valeurs existent
            if (!empty($data)) {
                if (!empty($data['code'])) {
                    $query->where('ticket.ticket_code', '=', $data['code']);
                }
                if (!empty($data['site'])) {
                    $query->where('site.site_id', '=', $data['site']);
                }
                if (!empty($data['typeaction'])) {
                    $query->where('type_action.type_action_id', '=', $data['typeaction']);
                }
                if (!empty($data['datedeclaration'])) {
                    $query->where('ticket.site_date_creation', '=', $data['datedeclaration']);
                }
            }

            $tickets = $query->get();

            //dd($data, $tickets);
            if($tickets->count() > 0){
                return Excel::download(new TicketExport($tickets), 'liste-des-ticket.xlsx');
            }else{
                return back()->with('info_warning',"Il n'a pas de données à exporter de la base de données");
            }
        }else{
            return back()->with('info_warning',"Vous n'êtes pas autorisé faire de exportation");
        }
    }

    public function exporterPDF()
    {  
        //Récupère les données de session ou un tableau vide
        $data = Session::get('recherche_data_ticket', []); 

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_003") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_006")){

            $query = Ticket::leftjoin('site', 'site.site_id', '=', 'ticket.site_id')
                            ->leftjoin('users', 'users.id', '=', 'ticket.user_id')
                            ->leftjoin('type_action', 'type_action.type_action_id', '=', 'ticket.type_action_id')
                            ->orderBy('ticket_code', 'DESC');

            // Appliquer les filtres seulement si des valeurs existent
            if (!empty($data)) {
                if (!empty($data['code'])) {
                    $query->where('ticket.ticket_code', '=', $data['code']);
                }
                if (!empty($data['site'])) {
                    $query->where('site.site_id', '=', $data['site']);
                }
                if (!empty($data['typeaction'])) {
                    $query->where('type_action.type_action_id', '=', $data['typeaction']);
                }
                if (!empty($data['datedeclaration'])) {
                    $query->where('ticket.site_date_creation', '=', $data['datedeclaration']);
                }
            }

            $tickets = $query->get();

            //dd($data, $sites);
            if($tickets->count() > 0){
                
                $html = View::make('exports.tickets', compact('tickets'))->render();

                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();

                // Ajouter la numérotation des pages en bas du document
                $canvas = $dompdf->getCanvas();
                $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
                    $text = "Page $pageNumber / $pageCount";
                    $font = $fontMetrics->getFont('Arial', 'normal');
                    $size = 10;
                    $width = $fontMetrics->getTextWidth($text, $font, $size);
                    $x = ($canvas->get_width() - $width) / 2;
                    $y = $canvas->get_height() - 30;
                    $canvas->text($x, $y, $text, $font, $size);
                });

                return $dompdf->stream("liste-des-tickets.pdf");
            }else{
                return back()->with('info_warning',"Il n'a pas de données à exporter de la base de données");
            }
        }else{
            return back()->with('info_warning',"Vous êtes autorisé faire de exportation");
        }
    }
}
