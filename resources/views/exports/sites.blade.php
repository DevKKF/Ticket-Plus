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
    @foreach($sites as $key => $site)
        @if($loop->first)
            <div class="header">
                <h1>Liste des sites</h1>
            </div>
            <br><br><br><br><br>
        @endif

        @if($loop->first)
            <table>
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%">N°</th>
                        <th class="text-center" style="width: 10%">Site IHS</th>
                        <th class="text-center" style="width: 15%">Site Name</th>
                        <th class="text-center" style="width: 10%">Region</th>
                        <th class="text-center" style="width: 10%">Zone</th>
                        <th class="text-center" style="width: 10%">Operateur</th>
                        <th class="text-center" style="width: 10%">Priority IHS</th>
                        <th class="text-center" style="width: 10%">Topology / Typology</th>
                        <th class="text-center" style="width: 10%">SBC</th>
                    </tr>
                </thead>
                <tbody>
        @endif

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

        @if($loop->last)
                </tbody>
            </table>
        @endif
    @endforeach

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} sur {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html> 