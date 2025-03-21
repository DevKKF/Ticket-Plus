<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Region;
use App\Models\TypeAction;
use App\Models\Zone;
use App\Models\Operateur;
use App\Models\PrioriteIHS;
use App\Models\TopologieTypologie;
use App\Models\Action;
use App\Models\ActionAutorisee;
use App\Models\Profil;
use App\Models\Site;
use App\Models\SiteUser;
use App\Models\Ticket;
use Carbon\Carbon;

class APIController extends Controller
{
    public function chargementRegionZone($region_id)
    {
        // Récupérer les zones associées à la région via l'ID
        $zones = Zone::where('region_id', $region_id)->get();

        // Retourner les zones en JSON
        return response()->json($zones);
    }

    //Liste des actions par profil des utilisateurs
    public function getActions($profil_id){

        $profil = Profil::find($profil_id);

        $actions = Action::where(['action.profil_id'=>$profil->profil_id, 'action.action_statut'=>"VALIDE"])->get();

        return response()->json($actions);
    }

    public function getActionsModification($profil_id, $user_id = null){
        $profil = Profil::find($profil_id);
        
        $actions = Action::where([
            'action.profil_id' => $profil->profil_id,
            'action.action_statut' => "VALIDE"
        ])->get();

        // Récupérer les actions déjà autorisées à l'utilisateur
        $action_autorisees = ActionAutorisee::where('user_id', $user_id)->pluck('action_id')->toArray();

        return response()->json([
            'actions' => $actions,
            'action_autorisees' => $action_autorisees
        ]);
    }

    public function updateStatut($id, Request $request)
    {
        $action = ActionAutorisee::find($id);
        
        if (!$action) {
            return response()->json(['success' => false, 'message' => 'Action autorisée non trouvée'], 404);
        }

        // Inverser le statut
        $action->action_autorisee_statut = ($request->status === 'VALIDE') ? 'VALIDE' : 'BROUILLON';
        $action->save();

        return response()->json(['success' => true, 'new_status' => $action->action_autorisee_statut]);
    }

    public function ChargementSiteInfo($site_id)
    {
        $site = Site::leftjoin('region', 'region.region_id', 'site.region_id')
                    ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                    ->leftjoin('operateur', 'operateur.operateur_id', 'site.operateur_id')
                    ->where('site_id', $site_id)
                    ->select(
                        'site.site_ihs',
                        'site.site_nom',
                        'operateur.operateur_nom',
                        'zone.zone_nom'
                    )
                    ->first();

        // Retourner le site en JSON
        return response()->json($site);
    }

    public function AjaxStatistiqueJournaliere(Request $request)
    {
        $siteId = $request->input('site_id');
        $typeActionId = $request->input('type_action_id');

        // Récupère les tickets pour le jour courant
        $tickets = Ticket::whereDate('ticket_datedeclaration', Carbon::today())
            ->when($siteId, function ($query, $siteId) {
                return $query->where('site_id', $siteId);
            })
            ->when($typeActionId, function ($query, $typeActionId) {
                return $query->where('type_action_id', $typeActionId);
            })
            ->with('user') // Charge la relation user (au lieu de utilisateur)
            ->get();

        // Traite les données pour obtenir les pourcentages par utilisateur
        $donnees = [];
        $totalTickets = $tickets->count();

        if ($totalTickets > 0) {
            $ticketsParUtilisateur = $tickets->groupBy('user_id'); // Utilisez user_id
            foreach ($ticketsParUtilisateur as $userId => $ticketsUtilisateur) {
                // Récupère l'utilisateur en utilisant la relation 'user'
                $utilisateur = $ticketsUtilisateur->first()->user;

                $pourcentage = ($ticketsUtilisateur->count() / $totalTickets) * 100;
                $donnees[] = [
                    'utilisateur' => $utilisateur, // Utilisez l'utilisateur récupéré
                    'pourcentage' => round($pourcentage)
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $donnees
        ]);
    }

    public function ChargementUserSite($site_id)
    {
        // Récupérer les users associées au site via l'ID
        $users = SiteUser::leftjoin('users', 'users.id', 'site_user.user_id')->where('site_user.site_id', $site_id)->get();

        // Retourner les users en JSON
        return response()->json($users);
    }
        
}