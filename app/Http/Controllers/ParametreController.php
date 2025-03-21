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
use App\Models\Ticket;

use Stdfn;
use Carbon\Carbon;

class ParametreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Liste des régions
    public function GestionRegion()
    {

        $regions = Region::orderby('region_id','DESC')->get();
        
        //dd($regions);
        return view('parametre.gestion_region', ['regions' => $regions]);
    }

    //Save région
    public function SaveRegion(Request $request)
    {

        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            'statut' => 'required',
            'nom' => 'required',
        ], [
            'statut.required' => "Le statut de la région est obligatoire.",
            'nom.required' => "Le nom de la région est obligatoire.",
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $region = new Region();

        $region->region_nom   	  = htmlspecialchars($request->nom);
        $region->region_statut    = htmlspecialchars($request->statut);
        $region->region_datecrea  = gmdate('Y-m-d H:i:s');
        $region->save();

        return back()->with('success', 'Région enregistrée avec succès !');
    }

    //Save modifier région
    public function ModifierRegion(Request $request, $region_id)
    {

        $region = Region::find($region_id);

        if (!empty($region)) {

            // Valider les données du formulaire
            $validator = Validator::make($request->all(), [
                'statut' => 'required',
                'nom' => 'required',
            ], [
                'statut.required' => "Le statut de la région est obligatoire.",
                'nom.required' => "Le nom de la région est obligatoire.",
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $region->region_nom   	  = htmlspecialchars($request->nom);
            $region->region_statut    = htmlspecialchars($request->statut);
            $region->exists 		  = true;
            $region->save();

            return back()->with('success', 'Région modifiée avec succès !');

        } else {
            return back()->with('warning', 'Région non trouvée !');
        }
    }

    //Supprimer région
    public function SupprimerRegion(Request $request)
    {

        $region_id = $request->region_id;

        $region = Region::find($region_id);

        if (!empty($region)) {

            $region->delete();

            echo 1;
        } else {
            echo 0;
        }
    }

    //Liste des régions
    public function GestionTypeAction()
    {

        $typeactions = TypeAction::orderby('type_action_id','DESC')->get();
        
        //dd($typeactions);
        return view('parametre.gestion_type_action', ['typeactions' => $typeactions]);
    }
    
    //Save type d'action
    public function SaveTypeAction(Request $request){

        $validator = Validator::make($request->all(), [
            'statut' => 'required',
            'nom' => 'required',
            'type_action_code' => 'required|unique:type_action',
        ], [
            'statut.required' => "Le statut du type d'action est obligatoire.",
            'nom.required' => "Le nom du type d'action est obligatoire.",
            'type_action_code.unique' => "Le code du type d'action est déjà utilisé.",
            'type_action_code.required' => "Le code du type d'action est obligatoire.",
        ]);
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $type_action = new TypeAction();

        $type_action->type_action_code      = htmlspecialchars($request->type_action_code);
        $type_action->type_action_nom       = htmlspecialchars($request->nom);
        $type_action->type_action_statut    = htmlspecialchars($request->statut);
        $type_action->type_action_datecrea  = gmdate('Y-m-d H:i:s');
        $type_action->save();

        return back()->with('success',"Type action enregsitré avec succès !");
    }

    //Save Modifier type d'action
    public function ModifierTypeAction(Request $request, $type_action_id)
    {

        $typeaction = TypeAction::find($type_action_id);

        if($typeaction){

            $validator = Validator::make($request->all(), [
                'statut' => 'required',
                'nom' => 'required',
                'type_action_code' => 'required|unique:type_action,type_action_code,' . $typeaction->type_action_id . ',type_action_id',
            ], [
                'statut.required' => "Le statut du type d'action est obligatoire.",
                'nom.required' => "Le nom du type d'action est obligatoire.",
                'type_action_code.unique' => "Le code du type d'action est déjà utilisé.",
                'type_action_code.required' => "Le code du type d'action est obligatoire.",
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $typeaction->type_action_code     = htmlspecialchars($request->type_action_code);
            $typeaction->type_action_nom      = htmlspecialchars($request->nom);
            $typeaction->type_action_statut   = htmlspecialchars($request->statut);
            $typeaction->exists               = true;
            $typeaction->update();

            return back()->with('success',"Type action modifié avec succès !");

        }else{

            return back()->with('warning',"Type action non trouvé !");

        }

    }

    //Supprimer type d'action
	public function SupprimerTypeAction(Request $request)
	{

		$type_action_id = $request->type_action_id;

		$type_action = TypeAction::find($type_action_id);

		if (!empty($type_action)) {

			$type_action->delete();

			echo 1;
		} else {
			echo 0;
		}
	}

    //Liste des zones
    public function GestionZone()
	{

		$regions = Region::where(['region_statut'=>"VALIDE"])->orderby('region_nom','ASC')->get();
		
        $zones = Zone::leftjoin('region', 'region.region_id', 'zone.region_id')->orderby('zone_id','DESC')->get();

		//dd($zones);
		return view('parametre.gestion_zone', ['zones' => $zones, 'regions' => $regions]);
	}

    //Save zone
	public function SaveZone(Request $request)
	{

        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            'statut' => 'required',
            'nom' => 'required',
            'region_id' => 'required',
        ], [
            'statut.required' => "Le statut de la zone est obligatoire.",
            'nom.required' => "Le nom de la zone est obligatoire.",
            'region_id.required' => "La région est obligatoire.",
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

		$zone = new Zone();

        $zone->region_id   	  = $request->region_id;
		$zone->zone_nom   	  = htmlspecialchars($request->nom);
		$zone->zone_statut    = htmlspecialchars($request->statut);
		$zone->zone_datecrea  = gmdate('Y-m-d H:i:s');
		$zone->save();

		return back()->with('success', 'Zone enregistrée avec succès !');
	}

	//Save modifier zone
	public function ModifierZone(Request $request, $zone_id)
	{

		$zone = Zone::find($zone_id);

		if (!empty($zone)) {

            // Valider les données du formulaire
            $validator = Validator::make($request->all(), [
                'statut' => 'required',
                'nom' => 'required',
                'region_id' => 'required',
            ], [
                'statut.required' => "Le statut de la zone est obligatoire.",
                'nom.required' => "Le nom de la zone est obligatoire.",
                'region_id.required' => "La région est obligatoire.",
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

			$zone->region_id   	  = $request->region_id;
			$zone->zone_nom   	  = htmlspecialchars($request->nom);
			$zone->zone_statut    = htmlspecialchars($request->statut);
			$zone->exists 		  = true;
			$zone->save();

			return back()->with('success', 'Zone modifiée avec succès !');

		} else {
			return back()->with('warning', 'Zone non trouvée !');
		}
	}

	//Supprimer zone
	public function SupprimerZone(Request $request)
	{

		$zone_id = $request->zone_id;

		$zone = Zone::find($zone_id);

		if (!empty($zone)) {

			$zone->delete();

			echo 1;
		} else {
			echo 0;
		}
	}

    //Liste des opérateurs
    public function GestionOperateur()
    {

        $operateurs = Operateur::orderby('operateur_id','DESC')->get();

        //dd($operateurs);
        return view('parametre.gestion_operateur', ['operateurs' => $operateurs]);
    }

    //Save opérateur
    public function SaveOperateur(Request $request)
    {

        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            'statut' => 'required',
            'nom' => 'required',
        ], [
            'statut.required' => "Le statut de l'opérateur est obligatoire.",
            'nom.required' => "Le nom de l'opérateur est obligatoire.",
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $operateur = new Operateur();

        $operateur->operateur_nom   	= htmlspecialchars($request->nom);
        $operateur->operateur_statut    = htmlspecialchars($request->statut);
        $operateur->operateur_datecrea  = gmdate('Y-m-d H:i:s');
        $operateur->save();

        return back()->with('success', 'Opérateur enregistrée avec succès !');
    }

    //Save modifier opérateur
    public function ModifierOperateur(Request $request, $operateur_id)
    {

        $operateur = Operateur::find($operateur_id);

        if (!empty($operateur)) {

            // Valider les données du formulaire
            $validator = Validator::make($request->all(), [
                'statut' => 'required',
                'nom' => 'required',
            ], [
                'statut.required' => "Le statut de l'opérateur est obligatoire.",
                'nom.required' => "Le nom de l'opérateur est obligatoire.",
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $operateur->operateur_nom   	= htmlspecialchars($request->nom);
            $operateur->operateur_statut    = htmlspecialchars($request->statut);
            $operateur->exists 		        = true;
            $operateur->save();

            return back()->with('success', 'Opérateur modifié avec succès !');

        } else {
            return back()->with('warning', 'Opérateur non trouvé !');
        }
    }

    //Supprimer opérateur
    public function SupprimerOperateur(Request $request)
    {

        $operateur_id = $request->operateur_id;

        $operateur = Operateur::find($operateur_id);

        if (!empty($operateur)) {

            $operateur->delete();

            echo 1;
        } else {
            echo 0;
        }
    }

    //Liste des priorités IHS
    public function GestionPrioriteIHS()
    {

        $priorites = PrioriteIHS::orderby('priorite_ihs_id','DESC')->get();

        //dd($priorites);
        return view('parametre.gestion_priorite_ihs', ['priorites' => $priorites]);
    }

    //Save priorités IHS
    public function SavePrioriteIHS(Request $request)
    {

        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            'statut' => 'required',
            'nom' => 'required',
        ], [
            'statut.required' => "Le statut de l'opérateur est obligatoire.",
            'nom.required' => "Le nom de l'opérateur est obligatoire.",
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $priorite_ihs = new PrioriteIHS();

        $priorite_ihs->priorite_ihs_nom   	  = htmlspecialchars($request->nom);
        $priorite_ihs->priorite_ihs_statut    = htmlspecialchars($request->statut);
        $priorite_ihs->priorite_ihs_datecrea  = gmdate('Y-m-d H:i:s');
        $priorite_ihs->save();

        return back()->with('success', 'Priorité IHS enregistrée avec succès !');
    }

    //Save modifier priorités IHS
    public function ModifierPrioriteIHS(Request $request, $priorite_ihs_id)
    {

        $priorite_ihs = PrioriteIHS::find($priorite_ihs_id);

        if (!empty($priorite_ihs)) {

            // Valider les données du formulaire
            $validator = Validator::make($request->all(), [
                'statut' => 'required',
                'nom' => 'required',
            ], [
                'statut.required' => "Le statut de l'opérateur est obligatoire.",
                'nom.required' => "Le nom de l'opérateur est obligatoire.",
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $priorite_ihs->priorite_ihs_nom   	= htmlspecialchars($request->nom);
            $priorite_ihs->priorite_ihs_statut  = htmlspecialchars($request->statut);
            $priorite_ihs->exists 		        = true;
            $priorite_ihs->save();

            return back()->with('success', 'Priorité IHS modifiée avec succès !');

        } else {
            return back()->with('warning', 'Priorité IHS non trouvés !');
        }
    }

    //Supprimer priorité IHS
    public function SupprimerPrioriteIHS(Request $request)
    {

        $priorite_ihs_id = $request->priorite_ihs_id;

        $priorite_ihs = PrioriteIHS::find($priorite_ihs_id);

        if (!empty($priorite_ihs)) {

            $priorite_ihs->delete();

            echo 1;
        } else {
            echo 0;
        }
    }
    
    //Liste des Topologies-Typologies
    public function GestionTopologieTypologie()
    {

        $topologietypologies = TopologieTypologie::orderby('topologie_typologie_id','DESC')->get();

        //dd($topologietypologies);
        return view('parametre.gestion_topologie_typologie', ['topologietypologies' => $topologietypologies]);
    }

    //Save Topologies-Typologies
    public function SaveTopologieTypologie(Request $request)
    {

        // Valider les données du formulaire
        $validator = Validator::make($request->all(), [
            'statut' => 'required',
            'nom' => 'required',
        ], [
            'statut.required' => "Le statut de l'opérateur est obligatoire.",
            'nom.required' => "Le nom de l'opérateur est obligatoire.",
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $topologie_typologie = new TopologieTypologie();

        $topologie_typologie->topologie_typologie_nom   	= htmlspecialchars($request->nom);
        $topologie_typologie->topologie_typologie_statut    = htmlspecialchars($request->statut);
        $topologie_typologie->topologie_typologie_datecrea  = gmdate('Y-m-d H:i:s');
        $topologie_typologie->save();

        return back()->with('success', 'Topologie-Typologie enregistrée avec succès !');
    }

    //Save modifier Topologies-Typologies
    public function ModifierTopologieTypologie(Request $request, $topologie_typologie_id)
    {

        $topologie_typologie = TopologieTypologie::find($topologie_typologie_id);

        if (!empty($topologie_typologie)) {

            // Valider les données du formulaire
            $validator = Validator::make($request->all(), [
                'statut' => 'required',
                'nom' => 'required',
            ], [
                'statut.required' => "Le statut de l'opérateur est obligatoire.",
                'nom.required' => "Le nom de l'opérateur est obligatoire.",
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $topologie_typologie->topologie_typologie_nom     = htmlspecialchars($request->nom);
            $topologie_typologie->topologie_typologie_statut  = htmlspecialchars($request->statut);
            $topologie_typologie->exists 		              = true;
            $topologie_typologie->save();

            return back()->with('success', 'Topologie-Typologie modifiée avec succès !');

        } else {
            return back()->with('warning', 'Topologie-Typologie non trouvés !');
        }
    }

    //Supprimer Topologie-Typologie
    public function SupprimerTopologieTypologie(Request $request)
    {

        $topologie_typologie_id = $request->topologie_typologie_id;

        $topologie_typologie = TopologieTypologie::find($topologie_typologie_id);

        if (!empty($topologie_typologie)) {

            $topologie_typologie->delete();

            echo 1;
        } else {
            echo 0;
        }
    }
}
