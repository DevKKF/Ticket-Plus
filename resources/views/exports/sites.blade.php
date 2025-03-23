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
        <h1>Liste des sites</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 3%">N°</th>
                <th class="text-center" style="width: 10%">Site IHS</th>
                <th class="text-center" style="width: 15%">Site Name</th>
                <th class="text-center" style="width: 5%">Region</th>
                <th class="text-center" style="width: 7%">Zone</th>
                <th class="text-center" style="width: 6%">Operateur</th>
                <th class="text-center" style="width: 7%">Priority IHS</th>
                <th class="text-center" style="width: 10%">Topology / Typology</th>
                <th class="text-center" style="width: 4%">SBC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sites as $key => $site)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $site->site_ihs }}</td>
                    <td>{{ $site->site_nom }}</td>
                    <td>{{ $site->region_nom }}</td>
                    <td>{{ $site->zone_nom }}</td>
                    <td>{{ $site->operateur_nom }}</td>
                    <td>{{ $site->priorite_ihs_nom }}</td>
                    <td>{{ $site->topologie_typologie_nom }}</td>
                    <td>{{ $site->site_sbc }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
