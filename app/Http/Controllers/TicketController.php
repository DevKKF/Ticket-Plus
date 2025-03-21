<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Region;
use App\Models\TypeAction;
use App\Models\Zone;
use App\Models\Operateur;
use App\Models\PrioriteIHS;
use App\Models\TopologieTypologie;
use App\Models\Site;
use App\Models\SiteUser;
use App\Models\Ticket;
use App\Models\HistoriqueTicket;

use Stdfn;
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


		return view('ticket.liste', [
            'tickets' => $tickets,  
            'sites' => $sites, 
            'typeactions' => $typeactions, 
            'selected_code'=>$code,
            'selected_site'=>$site,
            'selected_typeaction'=>$typeaction,
            'selected_datedeclaration'=>$datedeclaration,
        ]);
	}
 
    //Détails un ticket
    public function DetailsTicket(Request $request, $ticket_id)
	{
        
        $ticket = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                        ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                        ->leftjoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                        ->find($ticket_id);

		if (!empty($ticket)) {

            return view('ticket.details', [
                'ticket' => $ticket, 
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

            return view('ticket.modifier', [
                'ticket' => $ticket, 
                'sites' => $sites, 
                'typeactions' => $typeactions, 
            ]);

        } else {
            return back()->with('warning', 'Ticket non trouvé !');
        }
	}

    //Save Modifier un ticket
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
            $historique_ticket->user_id                            = $ticket->user_id;
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
            
            //Mis à jour de quittance
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
            $ticket_pm = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->whereBetween('type_action.type_action_code',['PM','PMEX'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->orderby('ticket_id','DESC')
                                ->get();

        }else{
            $ticket_pm = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->where(['ticket.user_id'=>Auth::user()->id])
                                ->whereBetween('type_action.type_action_code',['PM','PMEX'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->get();
                    
        }

        $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')->where(['site_statut'=>"VALIDE"])->orderby('site_nom','ASC')->get();
		$typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();

        return view('ticket.ticket_pm',[ 
            'ticket_pm' => $ticket_pm, 
            'sites' => $sites, 
            'typeactions' => $typeactions, 
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
            $ticket_cm = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->whereBetween('type_action.type_action_code',['CM','MC'])
                                ->whereRaw($whereRaw)
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->orderby('ticket_id','DESC')
                                ->get();

        }else{

            $ticket_cm = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
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

        return view('ticket.ticket_cm',[
            'ticket_cm' => $ticket_cm, 
            'sites' => $sites, 
            'typeactions' => $typeactions, 
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
        $tickets = Ticket::join('site', 'site.site_id', '=', 'ticket.site_id')
            ->join('users', 'users.id', '=', 'ticket.user_id')
            ->join('type_action', 'type_action.type_action_id', '=', 'ticket.type_action_id')
            ->select('site.site_ihs', 'site.site_nom', 'site.site_sbc', 'ticket.*', 'users.*')
            ->orderBy('ticket.ticket_datecrea', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="tickets.csv"',
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            // Ajouter le BOM UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-têtes en UTF-8
            $headers = [
                'N° Ticket',
                'Site IHS',
                'Site Name',
                'Type',
                "Chef d'équipe / Technicien",
                "Contact 1",
                "Contact 2",
                'Date début',
                'Heure début',
                "Type d'action",
                'Tâches',
                'Remarque',
                'Date fin',
                'Heure fin'
            ];
            fputcsv($file, $headers);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_code,
                    $ticket->site_ihs,
                    $ticket->site_nom,
                    $ticket->site_sbc,
                    $ticket->technicien_nom,
                    $ticket->telephone,
                    $ticket->autre_telephone,
                    $ticket->ticket_datedebut,
                    $ticket->ticket_heuredebut,
                    $ticket->type_action_nom,
                    $ticket->ticket_tacherealisee,
                    $ticket->ticket_remarque,
                    $ticket->ticket_datefin,
                    $ticket->ticket_heurefin
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exporterExcel()
    {
        $tickets = Ticket::join('site', 'site.site_id', '=', 'ticket.site_id')
            ->join('users', 'users.id', '=', 'ticket.user_id')
            ->join('type_action', 'type_action.type_action_id', '=', 'ticket.type_action_id')
            ->select('site.*', 'ticket.*', 'type_action.*', 'users.*')
            ->orderBy('ticket.ticket_datecrea', 'desc')
            ->get();

        return response()->streamDownload(function() use ($tickets) {
            $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $excel->getActiveSheet();

            // En-têtes
            $sheet->setCellValue('A1', 'N° Ticket');
            $sheet->setCellValue('B1', 'Site IHS');
            $sheet->setCellValue('C1', 'Site Name');
            $sheet->setCellValue('D1', 'Type');
            $sheet->setCellValue('E1', "Chef d'équipe / Technicien");
            $sheet->setCellValue('F1', "Contact 1");
            $sheet->setCellValue('G1', "Contact 2");
            $sheet->setCellValue('H1', 'Date début');
            $sheet->setCellValue('I1', 'Heure début');
            $sheet->setCellValue('J1', 'Type d\'action');
            $sheet->setCellValue('K1', 'Tâches');
            $sheet->setCellValue('L1', 'Remarque');
            $sheet->setCellValue('M1', 'Date fin');
            $sheet->setCellValue('N1', 'Heure fin');

            // Données
            $row = 2;
            foreach ($tickets as $ticket) {
                $sheet->setCellValue('A' . $row, $ticket->ticket_code);
                $sheet->setCellValue('B' . $row, $ticket->site_ihs);
                $sheet->setCellValue('C' . $row, $ticket->site_nom);
                $sheet->setCellValue('D' . $row, $ticket->site_sbc);
                $sheet->setCellValue('E' . $row, $ticket->nom_prenoms);
                $sheet->setCellValue('F' . $row, $ticket->telephone);
                $sheet->setCellValue('G' . $row, $ticket->autre_telephone);
                $sheet->setCellValue('H' . $row, $ticket->ticket_datedebut);
                $sheet->setCellValue('I' . $row, $ticket->ticket_heuredebut);
                $sheet->setCellValue('J' . $row, $ticket->type_action_nom);
                $sheet->setCellValue('K' . $row, $ticket->ticket_tacherealisee);
                $sheet->setCellValue('L' . $row, $ticket->ticket_remarque);
                $sheet->setCellValue('M' . $row, $ticket->ticket_datefin);
                $sheet->setCellValue('N' . $row, $ticket->ticket_heurefin);
                $row++;
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
            $writer->save('php://output');
        }, 'tickets.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="tickets.xlsx"'
        ]);
    }

    public function exporterPDF()
    {
        $tickets = Ticket::join('site', 'site.site_id', '=', 'ticket.site_id')
            ->join('users', 'users.id', '=', 'ticket.user_id')
            ->join('type_action', 'type_action.type_action_id', '=', 'ticket.type_action_id')
            ->orderBy('ticket.ticket_datecrea', 'desc')
            ->get();

        $pdf = \PDF::loadView('exports.tickets', ['tickets' => $tickets])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isPhpEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_left' => 15,
                'margin_right' => 15,
            ]);

        return $pdf->download('tickets.pdf');
    }
}
