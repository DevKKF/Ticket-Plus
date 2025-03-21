<?php

namespace App\Imports;

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

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class SiteImport implements ToModel, WithStartRow, WithCalculatedFormulas, WithCustomCsvSettings
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if (!empty($row[0])) {
            try {
                // Nettoyer les valeurs des formules Excel
                $siteIhs = $this->cleanExcelFormula($row[0]);
                $siteName = $this->cleanExcelFormula($row[1]);
                $regionName = $this->cleanExcelFormula($row[2]);
                $zoneName = $this->cleanExcelFormula($row[3]);
                $operateurName = $this->cleanExcelFormula($row[4]);
                $prioriteIhsName = $this->cleanExcelFormula($row[5]);
                $topologieTypologieName = $this->cleanExcelFormula($row[6]);
                $siteSbc = $this->cleanExcelFormula($row[7] ?? 'TBC');

                // Vérifier si les valeurs requises sont présentes
                if (empty($siteIhs) || empty($siteName)) {
                    Log::warning('Ligne ignorée - données requises manquantes:', ['row' => $row]);
                    return null;
                }

                // Rechercher ou créer la région
                $region = Region::firstOrCreate(
                    ['region_nom' => $regionName],
                    [
                        'region_nom' => $regionName,
                        'region_statut' => 'VALIDE',
                        'region_datecrea' => now()
                    ]
                );

                // Rechercher ou créer la zone
                $zone = Zone::firstOrCreate(
                    ['zone_nom' => $zoneName],
                    [
                        'zone_nom' => $zoneName,
                        'region_id' => $region->region_id,
                        'zone_statut' => 'VALIDE',
                        'zone_datecrea' => now()
                    ]
                );

                // Rechercher ou créer l'opérateur
                $operateur = Operateur::firstOrCreate(
                    ['operateur_nom' => $operateurName],
                    [
                        'operateur_nom' => $operateurName,
                        'operateur_statut' => 'VALIDE',
                        'operateur_datecrea' => now()
                    ]
                );

                // Rechercher ou créer la priorité IHS
                $prioriteIHS = PrioriteIHS::firstOrCreate(
                    ['priorite_ihs_nom' => $prioriteIhsName],
                    [
                        'priorite_ihs_nom' => $prioriteIhsName,
                        'priorite_ihs_statut' => 'VALIDE',
                        'priorite_ihs_datecrea' => now()
                    ]
                );

                // Rechercher ou créer la topologie/typologie
                $topologieTypologie = TopologieTypologie::firstOrCreate(
                    ['topologie_typologie_nom' => $topologieTypologieName],
                    [
                        'topologie_typologie_nom' => $topologieTypologieName,
                        'topologie_typologie_statut' => 'VALIDE',
                        'topologie_typologie_datecrea' => now()
                    ]
                );

                // Vérifier si le site existe déjà
                $existingSite = Site::where('site_ihs', $siteIhs)->first();

                if ($existingSite) {
                    $existingSite->update([
                        'site_nom' => $siteName,
                        'region_id' => $region->region_id,
                        'zone_id' => $zone->zone_id,
                        'operateur_id' => $operateur->operateur_id,
                        'priorite_ihs_id' => $prioriteIHS->priorite_ihs_id,
                        'topologie_typologie_id' => $topologieTypologie->topologie_typologie_id,
                        'site_sbc' => $siteSbc,
                        'site_datemodif' => now(),
                        'modifierpar_id' => Auth::id()
                    ]);

                    return null;
                } else {
                    return new Site([
                        'site_ihs' => $siteIhs,
                        'site_nom' => $siteName,
                        'region_id' => $region->region_id,
                        'zone_id' => $zone->zone_id,
                        'operateur_id' => $operateur->operateur_id,
                        'priorite_ihs_id' => $prioriteIHS->priorite_ihs_id,
                        'topologie_typologie_id' => $topologieTypologie->topologie_typologie_id,
                        'site_sbc' => $siteSbc,
                        'site_statut' => 'VALIDE',
                        'site_date_creation' => now()->format('Y-m-d'),
                        'site_datecrea' => now(),
                        'creerpar_id' => Auth::id()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'importation:', [
                    'row' => $row,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        }

        return null;
    }

    /**
     * Nettoie une formule Excel pour obtenir la valeur réelle
     */
    private function cleanExcelFormula($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_string($value)) {
            // Si c'est une formule XLOOKUP
            if (strpos($value, '=_xlfn.XLOOKUP') !== false) {
                // Extraire la valeur entre les dernières guillemets
                preg_match_all('/\"([^\"]+)\"/', $value, $matches);
                if (!empty($matches[1])) {
                    // Prendre la dernière correspondance (qui devrait être la valeur)
                    return end($matches[1]);
                }
            }
            
            // Nettoyer les espaces en début et fin
            $value = trim($value);
            
            // Convertir les caractères spéciaux
            $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        }
        
        return $value;
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

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'input_encoding' => 'UTF-8'
        ];
    }
}
