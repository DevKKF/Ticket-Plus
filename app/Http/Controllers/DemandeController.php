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
use App\Models\User;

use App\Exports\TicketExport;

use Stdfn;
use View;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;

class DemandeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }  


    //Liste des demandes
    public function ListeDesDemandes(Request $request)
	{
        if(Auth::user()->profil_id == 1 or Auth::user()->profil_id == 2){
            
            //Changement du statut de la consultation.
            Demande::where(['demande.demande_consulter'=>"NON"])->update(['demande_consulter'=>'OUI', 'demande_date_consulte'=>Carbon::now()]);

            //Bloc de la recherche dans les demandes
            $utilisateur = $request->query('u', '');
            $action = $request->query('ta', '');
            $statut = $request->query('s', '');
            $datedemande = $request->query('d', '');

            $whereRaw = ' 1 ';

            if (!empty($utilisateur) || !empty($action) || !empty($statut) || !empty($datedemande)){

                if(!empty($utilisateur)){
                    $whereRaw .= ' AND users.id = "' . $utilisateur . '"';
                }

                if(!empty($action)){
                    $whereRaw .= ' AND action_ticket.action_ticket_id = "' . $action . '"';
                }

                if(!empty($statut)){
                    $whereRaw .= ' AND demande.demande_statut = "' . $statut . '"';
                }

                if (!empty($datedemande)) {
                    $whereRaw .= ' AND demande.demande_date = "' . $datedemande . '"';
                }
            }

            $demandes = Demande::join('users', 'users.id', 'demande.user_id')
                                ->join('ticket', 'ticket.ticket_id', 'demande.ticket_id')
                                ->join('action_ticket', 'action_ticket.action_ticket_id', 'demande.action_ticket_id')
                                ->orderBy('demande.demande_id', 'DESC')
                                ->whereRaw($whereRaw)
                                ->get();
            
            $utilisateurs = User::join('profil', 'profil.profil_id', 'users.profil_id')
                                ->whereNotIn('id', [Auth::id()])->where('users.profil_id', '>', 2)->orderBy('id', 'DESC')->get();

            $actionticket = ActionTicket::where(['action_ticket_statut'=>"VALIDE"])->orderby('action_ticket_id','ASC')->get();

            return view('demande.liste',[
                'demandes'=>$demandes, 
                'utilisateurs'=>$utilisateurs, 
                'actionticket'=>$actionticket,
                'selected_utilisateur'=>$utilisateur,
                'selected_action'=>$action,
                'selected_statut'=>$statut,
                'selected_datedemande'=>$datedemande,
            ]);

        }else{
            return back()->with('warning',"Vous n'avez pas d'accès à cette page !");
        }
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
}
