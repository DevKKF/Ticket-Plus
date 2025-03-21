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
use App\Models\User;
use App\Models\HistoriqueTicket;

use Stdfn;
use Carbon\Carbon;

class StatistiquesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Statistiques journaliere
    public function StatistiquesJournaliere()
	{
        $utilisateurs = User::join('profil', 'profil.profil_id', 'users.profil_id')
                            ->whereNotIn('id', [Auth::id()])
                            ->where('users.profil_id','>',1)
                            ->orderby('id','DESC')
                            ->get();

        $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                        ->where(['site_statut'=>"VALIDE"])
                        ->orderby('site_nom','ASC')
                        ->get();

        $typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();

		return view('statistiques.journaliere', [
            'utilisateurs' => $utilisateurs, 
            'sites' => $sites, 
            'typeactions' => $typeactions, 
        ]);

	}

}
