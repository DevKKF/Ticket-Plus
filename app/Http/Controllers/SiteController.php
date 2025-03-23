<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
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
use App\Models\ActionTicket;

use App\Imports\SiteImport;
use App\Exports\SiteExport;
 
use Str;
use Stdfn;
use View;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SiteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //Ajouter un site
    public function AjouterSite()
	{

		$regions = Region::where(['region_statut'=>"VALIDE"])->orderby('region_nom','ASC')->get();
		$typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();
		$prioriteihs = PrioriteIHS::where(['priorite_ihs_statut'=>"VALIDE"])->orderby('priorite_ihs_nom','ASC')->get();
		$topologietypologies = TopologieTypologie::where(['topologie_typologie_statut'=>"VALIDE"])->orderby('topologie_typologie_nom','ASC')->get();
		$operateurs = Operateur::where(['operateur_statut'=>"VALIDE"])->orderby('operateur_nom','ASC')->get();

		return view('site.ajouter', [
            'typeactions' => $typeactions, 
            'regions' => $regions, 
            'prioriteihs' => $prioriteihs, 
            'operateurs' => $operateurs, 
            'topologietypologies' => $topologietypologies
        ]);
	}

    //Save site
    public function SaveSite(Request $request){

        $validator = Validator::make($request->all(), [
            'datecreation' => 'required|date',
            'statut' => 'required',
            'topologie_typologie_id' => 'required',
            'priorite_ihs_id' => 'required',
            'operateur_id' => 'required',
            'zone_id' => 'required',
            'region_id' => 'required',
            'sbc' => 'required',
            'nom' => 'required',
            'site_ihs' => 'required|unique:site',
        ], [
            'datecreation.date' => "La date de création doit être au format AAAA/JJ/MM.",
            'datecreation.required' => "La date de création est obligatoire.",
            'statut.required' => "Le statut est obligatoire.",
            'topologie_typologie_id.required' => "La topologie / typologie est obligatoire.",
            'priorite_ihs_id.required' => "La priorité IHS est obligatoire.",
            'operateur_id.required' => "L'opérateur est obligatoire.",
            'zone_id.required' => "La zone est obligatoire.",
            'region_id.required' => "La région est obligatoire.",
            'sbc.required' => "Le SBC du site est obligatoire.",
            'nom.required' => "Le nom du site est obligatoire.",
            'site_ihs.unique' => "Le code du site est déjà utilisé.",
            'site_ihs.required' => "Le code du site est obligatoire.",
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $site = new Site();

        $site->creerpar_id              = Auth::id();
        $site->topologie_typologie_id   = $request->topologie_typologie_id;
        $site->priorite_ihs_id          = $request->priorite_ihs_id;
        $site->operateur_id             = $request->operateur_id;
        $site->region_id                = $request->region_id;
        $site->zone_id                  = $request->zone_id;
        $site->site_ihs                 = htmlspecialchars($request->site_ihs);
        $site->site_nom                 = htmlspecialchars($request->nom);
        $site->site_nom_mm              = htmlspecialchars($request->nom_mm);
        $site->site_nom_rm              = htmlspecialchars($request->nom_rm);
        $site->site_sbc                 = htmlspecialchars($request->sbc);
        $site->site_date_creation       = htmlspecialchars($request->datecreation);
        $site->site_statut              = htmlspecialchars($request->statut);
        $site->site_datecrea            = gmdate('Y-m-d H:i:s');
        $site->save();

        return back()->with('success',"Site enregsitré avec succès !");
    }

    //Liste des sites
    public function ListeSite(Request $request)
	{ 

        $code = $request->query('c', '');
        $zone = $request->query('z', '');
        $operateur = $request->query('o', '');
        $datecreation = $request->query('d', '');

        $whereRaw = ' 1 ';

        if (!empty($code) || !empty($zone) || !empty($operateur) || !empty($datecreation)){
            if(!empty($code)){
                $whereRaw .= ' AND site.site_ihs LIKE "%' . $code . '%"';
            }

            if(!empty($zone)){
                $whereRaw .= ' AND site.zone_id = "' . $zone . '"';
            }

            if(!empty($operateur)){
                $whereRaw .= ' AND site.operateur_id = "' . $operateur . '"';
            }

            if (!empty($datecreation)) {
                $whereRaw .= ' AND site.site_date_creation = "' . $datecreation . '"';
            }
        }

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001")or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_005")){

            $sites = Site::leftjoin('region', 'region.region_id', 'site.region_id')
                        ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                        ->leftjoin('operateur', 'operateur.operateur_id', 'site.operateur_id')
                        ->whereRaw($whereRaw)
                        ->orderby('site_id','DESC')
                        ->get();

        }else{

            $sites = SiteUser::leftjoin('site', 'site.site_id', 'site_user.site_id')
                            ->leftjoin('region', 'region.region_id', 'site.region_id')
                            ->leftjoin('operateur', 'operateur.operateur_id', 'site.operateur_id')
                            ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                            ->where(['site_statut'=>"VALIDE", 'site_user.user_id'=>Auth::user()->id])
                            ->get();
        }
		
        $zones = Zone::where(['zone_statut'=>"VALIDE"])->orderby('zone_nom','ASC')->get();
		$operateurs = Operateur::where(['operateur_statut'=>"VALIDE"])->orderby('operateur_nom','ASC')->get();

        // Stocker les données de recherche en session
        Session::put('recherche_data_site', [
            'code' => $code,
            'zone' => $zone,
            'operateur' => $operateur,
            'datecreation' => $datecreation,
        ]);

		return view('site.liste', [
            'sites' => $sites,  
            'zones' => $zones, 
            'operateurs' => $operateurs, 
            'selected_code'=>$code,
            'selected_zone'=>$zone,
            'selected_operateur'=>$operateur,
            'selected_datecreation'=>$datecreation,
        ]);
	}

    //Détails un site
    public function DetailsSite(Request $request, $site_id)
	{

        $site = Site::leftjoin('region', 'region.region_id', 'site.region_id')
                    ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                    ->leftjoin('operateur', 'operateur.operateur_id', 'site.operateur_id')
                    ->leftjoin('priorite_ihs', 'priorite_ihs.priorite_ihs_id', 'site.priorite_ihs_id')
                    ->leftjoin('topologie_typologie', 'topologie_typologie.topologie_typologie_id', 'site.topologie_typologie_id')
                    ->find($site_id);

		if (!empty($site)) {

            $code = $request->query('c', '');
            $typeaction = $request->query('t', '');
            $datedeclaration = $request->query('d', '');

            $whereRaw = ' 1 ';

            if (!empty($code) || !empty($typeaction) || !empty($datedeclaration)){
                if(!empty($code)){
                    $whereRaw .= ' AND ticket.ticket_code LIKE "%' . $code . '%"';
                }

                if(!empty($typeaction)){
                    $whereRaw .= ' AND ticket.type_action_id = "' . $typeaction . '"';
                }

                if (!empty($datedeclaration)) {
                    $whereRaw .= ' AND ticket.site_date_creation = "' . $datedeclaration . '"';
                }
            }
            if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_003")){
                $tickets = Ticket::leftjoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->where(['ticket.site_id'=>$site->site_id])
                                ->whereRaw($whereRaw)
                                ->orderby('ticket_id','DESC')
                                ->get();
            }else{
                $tickets = Ticket::leftjoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->where(['ticket.site_id'=>$site->site_id, 'ticket.user_id'=>Auth::user()->id])
                                ->whereRaw($whereRaw)
                                ->orderby('ticket_id','DESC')
                                ->get();
            }
            
            $typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();

            return view('site.details', [
                'site' => $site, 
                'tickets' => $tickets, 
                'typeactions' => $typeactions, 
                'selected_code'=>$code,
                'selected_typeaction'=>$typeaction,
                'selected_datedeclaration'=>$datedeclaration,
            ]);
        
        } else {
			return back()->with('warning', 'Site non trouvé !');
		}
	}

    //Nouveau ticket un site
    public function NouveauTicketSite(Request $request, $site_id)
	{

        $site = Site::leftjoin('region', 'region.region_id', 'site.region_id')
                    ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                    ->leftjoin('operateur', 'operateur.operateur_id', 'site.operateur_id')
                    ->leftjoin('priorite_ihs', 'priorite_ihs.priorite_ihs_id', 'site.priorite_ihs_id')
                    ->leftjoin('topologie_typologie', 'topologie_typologie.topologie_typologie_id', 'site.topologie_typologie_id')
                    ->find($site_id);

		if (!empty($site)) {


            $code = $request->query('c', '');
            $typeaction = $request->query('t', '');
            $datedeclaration = $request->query('d', '');

            $whereRaw = ' 1 ';

            if (!empty($code) || !empty($typeaction) || !empty($datedeclaration)){
                if(!empty($code)){
                    $whereRaw .= ' AND ticket.ticket_code LIKE "%' . $code . '%"';
                }

                if(!empty($typeaction)){
                    $whereRaw .= ' AND ticket.type_action_id = "' . $typeaction . '"';
                }

                if (!empty($datedeclaration)) {
                    $whereRaw .= ' AND ticket.site_date_creation = "' . $datedeclaration . '"';
                }
            }

            $tickets = Ticket::leftjoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                        ->where(['ticket.site_id'=>$site->site_id])
                        ->whereRaw($whereRaw)
                        ->orderby('ticket_id','DESC')
                        ->get();


            $typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();

            return view('site.nouveau_ticket', [
                'site' => $site, 
                'tickets' => $tickets, 
                'typeactions' => $typeactions, 
                'selected_code'=>$code,
                'selected_typeaction'=>$typeaction,
                'selected_datedeclaration'=>$datedeclaration,
            ]);
        
        } else {
			return back()->with('warning', 'Site non trouvé !');
		}
	} 

    //Save Nouveau ticket un site
    public function SaveNouveauTicketSite(Request $request, $site_id)
	{

        $site = Site::find($site_id);

		if (!empty($site)) {

            $validator = Validator::make($request->all(), [
                'date_declaration' => 'required|date',
                'remarque' => 'required',
                'taches_realisees' => 'required',
                'type_action_id' => 'required',
                'heure_fin' => 'required',
                'date_fin' => 'required|date|after_or_equal:date_debut',
                'heure_debut' => 'required',
                'date_debut' => 'required|date',
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
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
    
            $type_action_rec = TypeAction::find($request->type_action_id);
    
            // Générer le code du ticket
            $code_ticket = Stdfn::genererCodeTicket($site->site_sbc, $type_action_rec->type_action_code);
    
            $date_delaismodification = Carbon::parse($request->date_declaration)->addDays(2);
    
            $ticket = new Ticket();
    
            $ticket->user_id                 = Auth::id();
            $ticket->creerpar_id             = Auth::id();
            $ticket->site_id                 = $site->site_id;
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
        
        } else {
			return back()->with('warning', 'Site non trouvé !');
		}
	}

    //Modifier un site
    public function ModifierSite(Request $request, $site_id)
	{

        $site = Site::find($site_id);

		if (!empty($site)) {

            $regions = Region::where(['region_statut'=>"VALIDE"])->orderby('region_nom','ASC')->get();
            $typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();
            $prioriteihs = PrioriteIHS::where(['priorite_ihs_statut'=>"VALIDE"])->orderby('priorite_ihs_nom','ASC')->get();
            $topologietypologies = TopologieTypologie::where(['topologie_typologie_statut'=>"VALIDE"])->orderby('topologie_typologie_nom','ASC')->get();
            $operateurs = Operateur::where(['operateur_statut'=>"VALIDE"])->orderby('operateur_nom','ASC')->get();

            return view('site.modifier', [
                'site' => $site, 
                'typeactions' => $typeactions, 
                'regions' => $regions, 
                'prioriteihs' => $prioriteihs, 
                'operateurs' => $operateurs, 
                'topologietypologies' => $topologietypologies
            ]);
        
        } else {
			return back()->with('warning', 'Site non trouvé !');
		}
	}

    //Save Modifier un site
    public function SaveModifierSite(Request $request, $site_id)
	{

        $site = Site::find($site_id);

		if (!empty($site)) {

            $validator = Validator::make($request->all(), [
                'datecreation' => 'required|date',
                'statut' => 'required',
                'topologie_typologie_id' => 'required',
                'priorite_ihs_id' => 'required',
                'operateur_id' => 'required',
                'zone_id' => 'required',
                'region_id' => 'required',
                'sbc' => 'required',
                'nom' => 'required',
                'site_ihs' => 'required|unique:site,site_ihs,' . $site->site_id . ',site_id',
            ], [
                'datecreation.date' => "La date de création doit être au format AAAA/JJ/MM.",
                'datecreation.required' => "La date de création est obligatoire.",
                'statut.required' => "Le statut est obligatoire.",
                'topologie_typologie_id.required' => "La topologie / typologie est obligatoire.",
                'priorite_ihs_id.required' => "La priorité IHS est obligatoire.",
                'operateur_id.required' => "L'opérateur est obligatoire.",
                'zone_id.required' => "La zone est obligatoire.",
                'region_id.required' => "La région est obligatoire.",
                'sbc.required' => "Le SBC du site est obligatoire.",
                'nom.required' => "Le nom du site est obligatoire.",
                'site_ihs.unique' => "Le code du site est déjà utilisé.",
                'site_ihs.required' => "Le code du site est obligatoire.",
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $site->modifierpar_id           = Auth::id();
            $site->topologie_typologie_id   = $request->topologie_typologie_id;
            $site->priorite_ihs_id          = $request->priorite_ihs_id;
            $site->operateur_id             = $request->operateur_id;
            $site->region_id                = $request->region_id;
            $site->zone_id                  = $request->zone_id;
            $site->site_ihs                 = htmlspecialchars($request->site_ihs);
            $site->site_nom                 = htmlspecialchars($request->nom);
            $site->site_nom_mm              = htmlspecialchars($request->nom_mm);
            $site->site_nom_rm              = htmlspecialchars($request->nom_rm);
            $site->site_sbc                 = htmlspecialchars($request->sbc);
            $site->site_date_creation       = htmlspecialchars($request->datecreation);
            $site->site_statut              = htmlspecialchars($request->statut);
            $site->site_datemodif           = gmdate('Y-m-d H:i:s');
            $site->exists                   = true;
            $site->save();
    
            return redirect()->route('liste_site')->with('success',"Site modifié avec succès !");
        
        } else {
			return back()->with('warning', 'Site non trouvé !');
		}
	}

    //Enregistrement de l'importation des données dans la table site
    public function SaveImporterSite(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xls,xlsx|max:10000'
            ], [
                'file.required' => 'Veuillez sélectionner un fichier',
                'file.mimes' => 'Le fichier doit être au format Excel (.xls ou .xlsx)',
                'file.max' => 'La taille du fichier ne doit pas dépasser 10MB'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $file = $request->file('file');
            
            // Vérifier si le fichier est bien un Excel
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, ['xls', 'xlsx'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Le fichier doit être au format Excel (.xls ou .xlsx)'
                ], 400);
            }

            // Configuration pour Excel
            config(['excel.imports.heading_row' => false]);
            
            // Importer les données
            Excel::import(new SiteImport, $file);

            return response()->json([
                'status' => true,
                'message' => 'Importation réussie'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'importation:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de l\'importation: ' . $e->getMessage()
            ], 500);
        }
    }

    //Supprimer site
	public function SupprimerSite(Request $request)
	{

		$site_id = $request->site_id;

		$site = Site::find($site_id);

		if (!empty($site)) {

			$site->delete();

			echo 1;
		} else {
			echo 0;
		}
	}

    public function exporterCSV()
    {
        // Récupère les données de session ou un tableau vide
        $data = Session::get('recherche_data_site', []); 

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001")or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_005")){

            $query = Site::select('site_ihs', 'site_nom', 'region_nom', 'zone_nom', 'operateur_nom', 'priorite_ihs_nom', 'topologie_typologie_nom')
                        ->leftJoin('zone', 'zone.zone_id', '=', 'site.zone_id')
                        ->leftJoin('region', 'region.region_id', '=', 'site.region_id')
                        ->leftJoin('topologie_typologie', 'topologie_typologie.topologie_typologie_id', '=', 'site.topologie_typologie_id')
                        ->leftJoin('priorite_ihs', 'priorite_ihs.priorite_ihs_id', '=', 'site.priorite_ihs_id')
                        ->leftJoin('operateur', 'operateur.operateur_id', '=', 'site.operateur_id')
                        ->orderBy('site_ihs', 'DESC');

            if (!empty($data)) {
                if (!empty($data['code'])) {
                    $query->where('site.site_ihs', '=', $data['code']);
                }
                if (!empty($data['zone'])) {
                    $query->where('zone.zone_id', '=', $data['zone']);
                }
                if (!empty($data['operateur'])) {
                    $query->where('operateur.operateur_id', '=', $data['operateur']);
                }
                if (!empty($data['datecreation'])) {
                    $query->where('site.site_date_creation', '=', $data['datecreation']);
                }
            }

            $sites = $query->get();

            if($sites->count() > 0){
                return Excel::download(new SiteExport($sites), 'liste-des-sites.csv', \Maatwebsite\Excel\Excel::CSV);
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
        $data = Session::get('recherche_data_site', []); 

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001")or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_005")){

            $query = Site::select('site_ihs', 'site_nom', 'site_nom', 'region_nom', 'zone_nom', 'operateur_nom', 'priorite_ihs_nom', 'topologie_typologie_nom')
                        ->leftJoin('zone', 'zone.zone_id', '=', 'site.zone_id')
                        ->leftJoin('region', 'region.region_id', '=', 'site.region_id')
                        ->leftJoin('topologie_typologie', 'topologie_typologie.topologie_typologie_id', '=', 'site.topologie_typologie_id')
                        ->leftJoin('priorite_ihs', 'priorite_ihs.priorite_ihs_id', '=', 'site.priorite_ihs_id')
                        ->leftJoin('operateur', 'operateur.operateur_id', '=', 'site.operateur_id')
                        ->orderBy('site_ihs', 'DESC');

            // Appliquer les filtres seulement si des valeurs existent
            if (!empty($data)) {
                if (!empty($data['code'])) {
                    $query->where('site.site_ihs', '=', $data['code']);
                }
                if (!empty($data['zone'])) {
                    $query->where('zone.zone_id', '=', $data['zone']);
                }
                if (!empty($data['operateur'])) {
                    $query->where('operateur.operateur_id', '=', $data['operateur']);
                }
                if (!empty($data['datecreation'])) {
                    $query->where('site.site_date_creation', '=', $data['datecreation']);
                }
            }

            $sites = $query->get();

            //dd($data, $sites);
            if($sites->count() > 0){
                return Excel::download(new SiteExport($sites), 'liste-des-sites.xlsx');
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
        $data = Session::get('recherche_data_site', []); 

        if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001")or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_005")){
            
            $query = Site::select('site_ihs', 'site_sbc', 'site_nom', 'region_nom', 'zone_nom', 'operateur_nom', 'priorite_ihs_nom', 'topologie_typologie_nom')
                        ->leftJoin('zone', 'zone.zone_id', '=', 'site.zone_id')
                        ->leftJoin('region', 'region.region_id', '=', 'site.region_id')
                        ->leftJoin('topologie_typologie', 'topologie_typologie.topologie_typologie_id', '=', 'site.topologie_typologie_id')
                        ->leftJoin('priorite_ihs', 'priorite_ihs.priorite_ihs_id', '=', 'site.priorite_ihs_id')
                        ->leftJoin('operateur', 'operateur.operateur_id', '=', 'site.operateur_id')
                        ->orderBy('site_ihs', 'DESC');

            // Appliquer les filtres seulement si des valeurs existent
            if (!empty($data)) {
                if (!empty($data['code'])) {
                    $query->where('site.site_ihs', '=', $data['code']);
                }
                if (!empty($data['zone'])) {
                    $query->where('zone.zone_id', '=', $data['zone']);
                }
                if (!empty($data['operateur'])) {
                    $query->where('operateur.operateur_id', '=', $data['operateur']);
                }
                if (!empty($data['datecreation'])) {
                    $query->where('site.site_date_creation', '=', $data['datecreation']);
                }
            }

            $sites = $query->get();

            //dd($data, $sites);
            if($sites->count() > 0){
                
                $html = View::make('exports.sites', compact('sites'))->render();

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

                return $dompdf->stream("liste-des-sites.pdf");
            }else{
                return back()->with('info_warning',"Il n'a pas de données à exporter de la base de données");
            }
        }else{
            return back()->with('info_warning',"Vous êtes autorisé faire de exportation");
        }
    }
}
