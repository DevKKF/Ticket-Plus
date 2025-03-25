<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
use App\Models\Action;
use App\Models\ActionAutorisee;
use App\Models\Profil;
use App\Models\SiteUser;
use App\Models\User;
use App\Models\Ticket;
use App\Models\ActionTicket;

use App\Imports\UtilisateurImport;

use Str;
use Stdfn;
use Carbon\Carbon;

class UtilisateurController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    } 
    
    //Ajouter utilisateur
    public function AjouterUtilisateur(Request $request){

        $profils = Profil::where(['profil_statut'=>"VALIDE"])->where('profil_id','>',1)->orderby('profil_nom','ASC')->get();

        $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')->orderby('site_nom','ASC')->get();

        return view('utilisateur.ajouter',['profils'=>$profils, 'sites'=>$sites]);
    }

    //Save utilisateur
    public function SaveUtilisateur(Request $request){

        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            'profil_id' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|unique:users',
            'telephone' => 'required|unique:users',
            'nom_prenoms' => 'required',
        ], [
            'profil_id.required' => "Le niveau d'accès est obligatoire.",
            'password.confirmed' => "La confirmation du mot de passe n'est pas conforme au mot de passe",
            'password.min' => "Le mot de passe doit être d'au moins 6 caractères.",
            'password.required' => "Le mot de passe est obligatoire.",
            'email.unique' => "Le login saisi est déjà utilisé.",
            'email.required' => "Le login est obligatoire.",
            'telephone.unique' => "Le téléphone saisi est déjà utilisé.",
            'telephone.required' => "Le téléphone est obligatoire.",
            'nom_prenoms.required' => "Le nom et prénoms de l'utilisateur est obligatoire.",
        ]);

        // Si la validation échoue, redirigez l'utilisateur avec les erreurs
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        //Récupération des actions cochées
        $action_ids = $request->actions;

        if($action_ids !=null){

            if($request->profil_id == 3){
                //Récupération des actions cochées
                $site_ids = $request->site_ids;
                if($site_ids == null){
                    return back()->with('warning', "Vous avez choisi aucun site à gérer");
                }
            }

            $user = new User();

            if ($request->photo) {

                $extension = $request->file('photo')->getClientOriginalExtension();
                $filename = Str::random(32) . '.' . $extension;
                $request->photo->move(public_path('assets/admin/images/profil'), $filename);

                $user->user_photo   =$filename;
            }
            
            $user->creerpar_id      = Auth::id();
            $user->profil_id        = $request->profil_id;
            $user->nom_prenoms      = htmlspecialchars($request->nom_prenoms);
            $user->telephone        = htmlspecialchars($request->telephone);
            $user->autre_telephone  = htmlspecialchars($request->autre_telephone);
            $user->email            = htmlspecialchars($request->email);
            $user->password         = Hash::make($request->password);
            $user->user_statut      = "VALIDE";
            $user->save();

            //Enregsitrer de nouvelle actions autoris&es
            foreach($action_ids as $action_id){

                $action_autorisee = new ActionAutorisee();
                
                $action_autorisee->creerpar_id               = Auth::id();
                $action_autorisee->action_id                 = $action_id;
                $action_autorisee->user_id                   = $user->id;
                $action_autorisee->action_autorisee_datecrea = gmdate('Y-m-d H:i:s');
                $action_autorisee->action_autorisee_statut   = "VALIDE";
                $action_autorisee->save();
            }

            //Enregistrer les sites à gérer par l'utilisateur
            if($request->profil_id == 3){
                //Récupération des actions cochées
                $site_ids = $request->site_ids;

                foreach($site_ids as $site_id){

                    $site_user = new SiteUser();

                    $site_user->site_id             = $site_id;
                    $site_user->user_id             = $user->id;
                    $site_user->site_user_datecrea  = gmdate('Y-m-d H:i:s');
                    $site_user->site_user_statut    = "VALIDE";
                    $site_user->save();
                }
            }

            return back()->with('succes',"Utilisateur enregistré avec succuès !");

        }else{
            return back()->with('warning', "Vous n'avez cochez aucun niveau d'accès");
        }

    }

    //Liste des utilisateurs
    public function ListeUtilisateur(Request $request){

        $nom_prenoms = $request->query('np', '');
        $profil = $request->query('p', '');
        $datecreation = $request->query('d', '');

        $whereRaw = ' 1 ';

        if (!empty($nom_prenoms) || !empty($profil) || !empty($datecreation)){
            if(!empty($nom_prenoms)){
                $whereRaw .= ' AND users.nom_prenoms LIKE "%' . $nom_prenoms . '%"';
            }

            if(!empty($profil)){
                $whereRaw .= ' AND users.profil_id = "' . $profil . '"';
            }

            if (!empty($datecreation)) {
                $whereRaw .= ' AND users.created_at = "' . $datecreation . '"';
            }
        }
 
        $utilisateurs = User::join('profil', 'profil.profil_id', 'users.profil_id')
                    ->whereNotIn('id', [Auth::id()])
                    ->where('users.profil_id','>',1)
                    ->whereRaw($whereRaw)
                    ->orderby('id','DESC')
                    ->get();

        $profils = Profil::where(['profil_statut'=>"VALIDE"])->where('profil_id','>',1)->orderby('profil_nom','ASC')->get();
        
        return view('utilisateur.liste',[
            'utilisateurs'=>$utilisateurs, 
            'profils'=>$profils,
            'selected_nom_prenoms'=>$nom_prenoms,
            'selected_profil'=>$profil,
            'selected_datecreation'=>$datecreation,
        ]);
    }

    //Détails utilisateur
    public function DetailsUtilisateur(Request $request, $id){

        $utilisateur = User::leftjoin('profil', 'profil.profil_id', 'users.profil_id')->find($id);

		if (!empty($utilisateur)) {

            $action_autorisees = ActionAutorisee::leftjoin('action', 'action.action_id', 'action_autorisee.action_id')->where(['action_autorisee.user_id'=>$utilisateur->id])->get();

            return view('utilisateur.details',[
                'utilisateur' => $utilisateur, 
                'action_autorisees'=>$action_autorisees, 
            ]);

        } else {
            return back()->with('warning', 'Utilisateur non trouvé !');
        }
    }

    //Sites gérés
    public function SiteGeresUtilisateur(Request $request, $id){

        $utilisateur = User::leftjoin('profil', 'profil.profil_id', 'users.profil_id')->find($id);

		if (!empty($utilisateur)) {

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

            $site_gerer = SiteUser::leftjoin('site', 'site.site_id', 'site_user.site_id')
                    ->leftjoin('region', 'region.region_id', 'site.region_id')
                    ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                    ->leftjoin('operateur', 'operateur.operateur_id', 'site.operateur_id')
                    ->whereRaw($whereRaw)
                    ->where(['site_user.user_id'=>$utilisateur->id])
                    ->orderby('site_user.site_id','DESC')
                    ->get();

            $zones = Zone::where(['zone_statut'=>"VALIDE"])->orderby('zone_nom','ASC')->get();
		    $operateurs = Operateur::where(['operateur_statut'=>"VALIDE"])->orderby('operateur_nom','ASC')->get();

            return view('utilisateur.sites',[
                'utilisateur' => $utilisateur, 
                'site_gerer'=>$site_gerer, 
                'zones' => $zones, 
                'operateurs' => $operateurs, 
                'selected_code'=>$code,
                'selected_zone'=>$zone,
                'selected_operateur'=>$operateur,
                'selected_datecreation'=>$datecreation,
            ]);

        } else {
            return back()->with('warning', 'Utilisateur non trouvé !');
        }
    }

    //Tickets enregistrés
    public function TicketsEnregistresUtilisateur(Request $request, $id){

        $utilisateur = User::leftjoin('profil', 'profil.profil_id', 'users.profil_id')->find($id);

		if (!empty($utilisateur)) {

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
            
            $tickets = Ticket::leftjoin('site', 'site.site_id', 'ticket.site_id')
                        ->leftjoin('type_action', 'type_action.type_action_id', 'ticket.type_action_id')
                        ->whereRaw($whereRaw)
                        ->where(['ticket.user_id'=>$utilisateur->id])
                        ->orderby('ticket_id','DESC')
                        ->get();
            
            $sites = SiteUser::leftjoin('site', 'site.site_id', 'site_user.site_id')
                        ->leftjoin('region', 'region.region_id', 'site.region_id')
                        ->leftjoin('zone', 'zone.zone_id', 'site.zone_id')
                        ->leftjoin('operateur', 'operateur.operateur_id', 'site.operateur_id')
                        ->where(['site_user.user_id'=>$utilisateur->id])
                        ->orderby('site_user.site_id','DESC')
                        ->get();
                        
            $typeactions = TypeAction::where(['type_action_statut'=>"VALIDE"])->orderby('type_action_nom','ASC')->get();


            return view('utilisateur.tickets', [
                'utilisateur' => $utilisateur,  
                'tickets' => $tickets,  
                'sites' => $sites, 
                'typeactions' => $typeactions, 
                'selected_code'=>$code,
                'selected_site'=>$site,
                'selected_typeaction'=>$typeaction,
                'selected_datedeclaration'=>$datedeclaration,
            ]);

        } else {
            return back()->with('warning', 'Utilisateur non trouvé !');
        }
    }

    //Modifier utilisateur
    public function ModifierUtilisateur(Request $request, $id){

        $utilisateur = User::find($id);

		if (!empty($utilisateur)) {

            $profils = Profil::where(['profil_statut'=>"VALIDE"])->where('profil_id','>',1)->orderby('profil_nom','ASC')->get();

            $sites = Site::leftjoin('zone', 'zone.zone_id', 'site.zone_id')->orderby('site_nom','ASC')->get();
            $site_gerer = SiteUser::where(['site_user.user_id'=>$utilisateur->id])->get();

            return view('utilisateur.modifier',[
                'utilisateur' => $utilisateur, 
                'profils'=>$profils, 
                'site_gerer'=>$site_gerer, 
                'sites'=>$sites
            ]);

        } else {
            return back()->with('warning', 'Utilisateur non trouvé !');
        }
    }

    //Save Modifier utilisateur
    public function SaveModifierUtilisateur(Request $request, $id){

        $utilisateur = User::find($id);

		if (!empty($utilisateur)) {

            // Valider les données du formulaire
            $validator = Validator::make($request->all(), [
                'profil_id' => 'required',
                'password' => 'required|string|min:6|confirmed',
                'email' => 'required|unique:users,email,' . $utilisateur->id . ',id',
                'telephone' => 'required|unique:users,telephone,' . $utilisateur->id . ',id',
                'nom_prenoms' => 'required',
            ], [
                'profil_id.required' => "Le niveau d'accès est obligatoire.",
                'password.confirmed' => "La confirmation du mot de passe n'est pas conforme au mot de passe",
                'password.min' => "Le mot de passe doit être d'au moins 6 caractères.",
                'password.required' => "Le mot de passe est obligatoire.",
                'email.unique' => "Le login saisi est déjà utilisé.",
                'email.required' => "Le login est obligatoire.",
                'telephone.unique' => "Le téléphone saisi est déjà utilisé.",
                'telephone.required' => "Le téléphone est obligatoire.",
                'nom_prenoms.required' => "Le nom et prénoms de l'utilisateur est obligatoire.",
            ]);

            // Si la validation échoue, redirigez l'utilisateur avec les erreurs
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            //Récupération des actions cochées
            $action_ids = $request->actions;

            if($action_ids !=null){

                if($request->profil_id == 3){
                    //Récupération des actions cochées
                    $site_ids = $request->site_ids;
                    if($site_ids == null){
                        return back()->with('warning', "Vous avez choisi aucun site à gérer");
                    }
                }

                if ($request->photo) {

                    $extension = $request->file('photo')->getClientOriginalExtension();
                    $filename = Str::random(32) . '.' . $extension;
                    $request->photo->move(public_path('assets/admin/images'), $filename);
    
                    $utilisateur->user_photo   =$filename;
                }

                $utilisateur->modifierpar_id   = Auth::id();
                $utilisateur->nom_prenoms      = htmlspecialchars($request->nom_prenoms);
                $utilisateur->telephone        = htmlspecialchars($request->telephone);
                $utilisateur->autre_telephone  = htmlspecialchars($request->autre_telephone);
                $utilisateur->email            = htmlspecialchars($request->email);
                $utilisateur->password         = Hash::make($request->password);
                $utilisateur->exists           = true;
                $utilisateur->save();
                
                //Supprimer les anciennes actions autorisées
                $action_autorisees = ActionAutorisee::where(['action_autorisee.user_id'=>$utilisateur->id])->delete();

                //Enregsitrer de nouvelle actions autoris&es
                foreach($action_ids as $action_id){
    
                    $action_autorisee = new ActionAutorisee();
                    
                    $action_autorisee->creerpar_id               = Auth::id();
                    $action_autorisee->action_id                 = $action_id;
                    $action_autorisee->user_id                   = $utilisateur->id;
                    $action_autorisee->action_autorisee_datecrea = gmdate('Y-m-d H:i:s');
                    $action_autorisee->action_autorisee_statut   = "VALIDE";
                    $action_autorisee->save();
                }
    
                //Enregistrer les sites à gérer par l'utilisateur
                if($request->profil_id == 3){

                    //Supprimer les anciens sites gérés
                    $site_gerer = SiteUser::where(['site_user.user_id'=>$utilisateur->id])->delete();

                    //Récupération des actions cochées
                    $site_ids = $request->site_ids;
    
                    foreach($site_ids as $site_id){
    
                        $site_user = new SiteUser();
    
                        $site_user->site_id             = $site_id;
                        $site_user->user_id             = $utilisateur->id;
                        $site_user->site_user_datecrea  = gmdate('Y-m-d H:i:s');
                        $site_user->site_user_statut    = "VALIDE";
                        $site_user->save();
                    }
                }
    
                return redirect()->route('liste_utilisateur')->with('succes',"Utilisateur modifié avec succuès !");

            }else{
                return back()->with('warning', "Vous n'avez cochez aucun niveau d'accès");
            }

        } else {
            return back()->with('warning', 'Utilisateur non trouvé !');
        }
    }

    //Enregistrement de l'importation des données dans la table utilisateur
    public function SaveImporterUtilisateur(Request $request){

        $request->validate([
            'fichier' => 'required|mimes:csv,xlsx',
        ], [
            'fichier.required' => "Le fichier d'importation des techniciens est obligatoire.",
            'fichier.mimes' => "Le fichier doit être de type : csv, xlsx.",
        ]);

        Excel::import(new UtilisateurImport,request()->file('fichier'));

        return back()->with('succes',"Importation réussie !");
    }

    //Supprimer utilisateur
	public function SupprimerUtilisateur(Request $request)
	{

		$utilisateur_id = $request->utilisateur_id;

		$utilisateur = User::find($utilisateur_id);

		if (!empty($utilisateur)) {

            $tickets = Ticket::where('user_id', $utilisateur->id)->count();

            if ($tickets > 0) {
                
                return response()->json([
                    'status' => 0,
                    'message' => "L'utilisateur {$utilisateur->nom_prenoms} a {$tickets} ticket(s), donc nous ne pouvons pas le supprimer."
                ]);

            }else{

                ActionAutorisee::where('action_autorisee.user_id', $utilisateur->id)->delete();
                $tickets = Ticket::where(['ticket.user_id'=>$utilisateur->id])->delete();
                $site_gerer = SiteUser::where(['site_user.user_id'=>$utilisateur->id])->delete();

                $utilisateur->delete();

                return response()->json([
                    'status' => 1,
                    'message' => "Utilisateur supprimé avec succès !"
                ]);
            }

		} else {
			return response()->json([
                'status' => 0,
                'message' => "Utilisateur introuvable."
            ]);
		}
	}

    //Mon compte
    public function MonCompte(Request $request){

        $utilisateur = User::leftjoin('profil', 'profil.profil_id', 'users.profil_id')->find(Auth::id());

        if (!empty($utilisateur)) {

            $action_autorisees = ActionAutorisee::leftjoin('action', 'action.action_id', 'action_autorisee.action_id')->where(['action_autorisee.user_id'=>$utilisateur->id])->get();

            return view('moncompte.details',[
                'utilisateur' => $utilisateur, 
                'action_autorisees'=>$action_autorisees, 
            ]);

        } else {
            return back()->with('warning', 'Mon compte non trouvé !');
        }
    }

    //Mot de passe 
    public function ChangerMotPasse(Request $request){

        $utilisateur = User::leftjoin('profil', 'profil.profil_id', 'users.profil_id')->find(Auth::id());

        if (!empty($utilisateur)) {
            return view('moncompte.motdepasse',[
                'utilisateur' => $utilisateur, 
            ]);

        } else {
            return back()->with('warning', 'Mon compte non trouvé !');
        }
    }

    //Save changement mot de passe
    public function SaveChangerMotPasse(Request $request){

        $utilisateur = User::leftjoin('profil', 'profil.profil_id', 'users.profil_id')->find(Auth::id());

        if (!empty($utilisateur)) {

           // Valider les données du formulaire
            $validatedData = $request->validate([
                'password' => ['required', 'string', 'min:6'],
            ], [
                'password.required' => "Le nouveau mot de passe est obligatoire.",
                'password.min' => "Le nouveau mot de passe doit être d'au moins de 6 caractères.",
            ]);

            $utilisateur = User::where(['users.id'=>Auth::user()->id])->first();

            if($utilisateur){

                $utilisateur->password = Hash::make($request->password);
                $utilisateur->exists = true;
                $utilisateur->save();

                return back()->with('success', 'Mot de passe mis à jour avec succès.');
            }

        } else {
            return back()->with('warning', 'Mon compte non trouvé !');
        }
    }

}
