<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\ActionTicket;

use Stdfn;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable PMEX
     */
    public function index()
    {

        if(Auth::user()->profil_id == 1 or Auth::user()->profil_id == 2 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_007")){
            $ticket_cm = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->whereBetween('type_action.type_action_code',['CM','MC'])
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->count();

            $ticket_pm = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->whereBetween('type_action.type_action_code',['PM','PMEX'])
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->count();

            $autres_tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->whereNotIn('type_action.type_action_code',['CM','MC','PM','PMEX'])
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->count();

        }else{

            $ticket_cm = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->where(['ticket.user_id'=>Auth::user()->id])
                                ->whereBetween('type_action.type_action_code',['CM','MC'])
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->count();

            $ticket_pm = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                ->where(['ticket.user_id'=>Auth::user()->id])
                                ->whereBetween('type_action.type_action_code',['PM','PMEX'])
                                ->whereDate('ticket_datedeclaration', Carbon::today())
                                ->count();

            $autres_tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                                    ->join('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                                    ->where(['ticket.user_id'=>Auth::user()->id])
                                    ->whereNotIn('type_action.type_action_code',['CM','MC','PM','PMEX'])
                                    ->whereDate('ticket_datedeclaration', Carbon::today())
                                    ->count();
                    
        }

        return view('home',[
            'ticket_cm' => $ticket_cm,   
            'ticket_pm' => $ticket_pm,   
            'autres_tickets' => $autres_tickets,   
        ]);
    }
}
