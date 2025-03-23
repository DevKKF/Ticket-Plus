<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des sites</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Liste des tickets</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 3%">N°</th>
                <th class="text-center" style="width: 6%">N° Ticket</th>
                <th class="text-center" style="width: 6%">Site IHS</th>
                <th class="text-center" style="width: 10%">Site Name</th>
                <th class="text-center" style="width: 3%">Type</th>
                <th class="text-center" style="width: 12%">Chef d'équipe / Technicien</th>
                <th class="text-center" style="width: 8%">Contact 1</th>
                <th class="text-center" style="width: 8%">Contact 2</th>
                <th class="text-center" style="width: 6%">Date début</th>
                <th class="text-center" style="width: 7%">Heure début</th>
                <th class="text-center" style="width: 10%">Type d'action</th>
                <th class="text-center" style="width: 15%">Tâches</th>
                <th class="text-center" style="width: 6%">Date fin</th>
                <th class="text-center" style="width: 7%">Heure fin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $key => $ticket)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $ticket->ticket_code }}</td>
                    <td>{{ $ticket->site_ihs }}</td>
                    <td>{{ $ticket->site_nom }}</td>
                    <td>{{ $ticket->site_sbc }}</td>
                    <td>{{ $ticket->nom_prenoms }}</td>
                    <td>{{ $ticket->telephone }}</td>
                    <td>{{ $ticket->autre_telephone }}</td>
                    <td>{{ $ticket->ticket_datedebut }}</td>
                    <td>{{ $ticket->ticket_heuredebut }}</td>
                    <td>{{ $ticket->type_action_nom }}</td>
                    <td>{{ $ticket->ticket_tacherealisee }}</td>
                    <td>{{ $ticket->ticket_datefin }}</td>
                    <td>{{ $ticket->ticket_heurefin }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
