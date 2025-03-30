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
            Demande::where(['demande.demande_consulter'=>"NON"])->update(['demande_consulter'=>'OUI', 'demande_date_consulte'=>Carbon::now(), 'consulterpar_id'=>Auth::id()]);

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

    //Détails de la demande
    public function DetailsDemande(Request $request, $demande_id)
	{
        
        Demande::where(['demande.demande_consulter'=>"NON", 'demande.demande_id'=>$demande_id])->update(['demande_consulter'=>'OUI', 'demande_date_consulte'=>Carbon::now(), 'consulterpar_id'=>Auth::id()]);

        $demande = Demande::with(['enregistrer_par', 'consulter_par', 'valider_par', 'annuler_par'])
                        ->leftJoin('ticket', 'ticket.ticket_id', 'demande.ticket_id')
                        ->leftJoin('action_ticket', 'action_ticket.action_ticket_id', 'demande.action_ticket_id')
                        ->leftJoin('users as createur', 'createur.id', 'demande.user_id')
                        ->leftJoin('users as consulteur', 'consulteur.id', 'demande.consulterpar_id')
                        ->leftJoin('users as valideur', 'valideur.id', 'demande.validerpar_id')
                        ->leftJoin('users as annuleur', 'annuleur.id', 'demande.annulerpar_id')
                        ->select('demande.*', 'action_ticket.*', 'ticket.*')
                        ->where('demande.demande_id', $demande_id)
                        ->first();

		if (!empty($demande)) {

            $ticket = Ticket::with(['enregistrer_par', 'modifier_par'])
                            ->leftJoin('site', 'site.site_id', 'ticket.site_id')
                            ->leftJoin('zone', 'zone.zone_id', 'site.zone_id')
                            ->leftJoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                            ->leftJoin('users as createur', 'createur.id', 'ticket.creerpar_id')
                            ->leftJoin('users as modificateur', 'modificateur.id', 'ticket.modifierpar_id')
                            ->select('ticket.*', 'site.site_nom', 'zone.zone_nom', 'type_action.type_action_nom',
                                    'createur.nom_prenoms as createur_nom',
                                    'modificateur.nom_prenoms as modificateur_nom')
                            ->where('ticket.ticket_id', $demande->ticket_id)
                            ->first();

            $historiques = HistoriqueTicket::leftJoin('site', 'site.site_id', 'historique_ticket.site_id')
                        ->leftJoin('zone', 'zone.zone_id', 'site.zone_id')
                        ->leftJoin('users', 'users.id', 'historique_ticket.user_id')
                        ->leftJoin('type_action', 'type_action.type_action_id', 'historique_ticket.type_action_id')
                        ->where('historique_ticket.ticket_id', $ticket->ticket_id)
                        ->get();

            return view('demande.details', [
                'demande' => $demande, 
                'ticket' => $ticket, 
                'historiques' => $historiques, 
            ]);

        } else {
            return back()->with('warning', 'Demande non trouvée !');
        }
	}

    //Traitement de la demande
    public function TraitementDemande(Request $request, $demande_id)
	{
        $demande = Demande::find($demande_id);
        
        if (!empty($demande)) {

            $validator = Validator::make($request->all(), [
                'demande_date' => 'required|date',
                'action' => 'required',
            ], [            
                'demande_date.date' => "La date du traitement doit être au format AAAA/JJ/MM.",
                'demande_date.required' => "La date du traitement est obligatoire.",
                'action.required' => "L'action à faire est obligatoire.",
            ]);
            
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if($request->action == "ACCEPTE"){
                $demande->validerpar_id           = Auth::id();
                $demande->demande_date_valide     = gmdate('Y-m-d H:i:s');
            }else{
                $demande->annulerpar_id           = Auth::id();
                $demande->demande_date_annuelle   = gmdate('Y-m-d H:i:s');
            }
            $demande->demande_motif_annule        = htmlspecialchars($request->description);
            $demande->demande_statut              = htmlspecialchars($request->action);
            $demande->exists                      = true;
            $demande->save();
    
            return back()->with('success',"Demande traitée avec succès !");

        } else {
            return back()->with('warning', 'Demande non trouvés !');
        }
	}

}
