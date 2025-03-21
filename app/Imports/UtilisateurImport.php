<?php

namespace App\Imports;

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

//use ZipArchive;

use Stdfn;
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UtilisateurImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 4;
    }

    public function model(array $row){

        if(!empty($row[0])){

            $caracteresASupprimer = array(' ', '.', ';', ',', '/', '\\', '::', ':');

            // Vérifie si le site existe
            $existingsite = Site::where('site.site_ihs', $row[0])->first();

            // Vérifie si le mail existe déjà
            $existingutilisateur = User::where('users.email', $row[1])->first();

            if($existingutilisateur){
                if($existingsite){
                    $site_user = new SiteUser();

                    $site_user->site_id             = $existingsite->site_id;
                    $site_user->user_id             = $existingutilisateur->id;
                    $site_user->site_user_datecrea  = gmdate('Y-m-d H:i:s');
                    $site_user->site_user_statut    = "VALIDE";
                    $site_user->save();
                }else{
                    //Rien à faire
                }

            }else{

                $user = new User();
                
                $user->creerpar_id      = Auth::id();
                $user->profil_id        = 3;
                $user->email            = $row[1];
                $user->nom_prenoms      = $this->formatMajuscule($row[2]);
                $user->telephone        = $this->supprimerCaracteres($row[3], $caracteresASupprimer);
                $user->password         = Hash::make(12345678);
                $user->user_statut      = "VALIDE";
                $user->save();

                //Récupération des actions cochées
                $action_ids = Action::where('action.profil_id', 3)->get();
                
                //Enregsitrer de nouvelle actions autoris&es
                foreach($action_ids as $action_id){

                    $action_autorisee = new ActionAutorisee();
                    
                    $action_autorisee->creerpar_id               = Auth::id();
                    $action_autorisee->action_id                 = $action_id->action_id;
                    $action_autorisee->user_id                   = $user->id;
                    $action_autorisee->action_autorisee_datecrea = gmdate('Y-m-d H:i:s');
                    $action_autorisee->action_autorisee_statut   = "VALIDE";
                    $action_autorisee->save();
                }

                if($existingsite){
                    $site_user = new SiteUser();

                    $site_user->site_id             = $existingsite->site_id;
                    $site_user->user_id             = $user->id;
                    $site_user->site_user_datecrea  = gmdate('Y-m-d H:i:s');
                    $site_user->site_user_statut    = "VALIDE";
                    $site_user->save();
                }else{
                    //Rien à faire
                }

                return $user;
                
            }

        }else{

            return null; // Ne créez pas d'enregistrement pour les lignes vides

        }
    }

    private function formatMajuscule($nomVariable){

        return mb_strtoupper(Str::ascii($nomVariable));
    }

    public function rules(): array{
        
        return [
            // Définir les règles de validation si nécessaire
        ];
    }

    public function supprimerCaracteres($phrase, $caracteresASupprimer) {
        // Parcours du tableau de caractères à supprimer
        foreach ($caracteresASupprimer as $caractere) {
            // Remplacement de chaque caractère par une chaîne vide
            $phrase = str_replace($caractere, '', $phrase);
        }
    
        return $phrase;
    }   
}
