<?php

namespace App\Exports;

use App\Models\Site;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiteExport implements FromCollection, WithHeadings
{
    protected $sites;

    public function __construct($sites)
    {
        $this->sites = $sites;
    }

    public function collection()
    {
        return $this->sites;
    }

    public function headings():array
    {
        return [
            "Site ID IHS",
            "Site Name", 
            "Région", 
            "Zone", 
            "Operator", 
            "Priority IHS", 
            "Topology / Typology	SBC",
        ];

    }
}
