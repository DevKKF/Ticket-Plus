<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketExport implements FromCollection, WithHeadings
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets;
    }

    public function headings():array
    {
        return [
            "N° Ticket",
            "Site IHS", 
            "Site Name", 
            "Type", 
            "Chef d'équipe / Technicien", 
            "Contact 1", 
            "Contact 2",
            "Date début",
            "Heure début",
            "Type d'action",
            "Tâches",
            "Remarque",
            "Date fin",
            "Heure fin",
        ];

    }
}
