<table class="table table-hover table-sm">
    <thead class="thead-default">
    <tr>
        <th class="font-weight-bold">Résultat</th>
        <th class="font-weight-bold">Message</th>
        <th class="font-weight-bold">Nombre de Ligne(s) affectée(s)</th>
    </tr>
    </thead>
    <tbody>
        @forelse ($report as $report_line)
            <tr>
                <td>
                    @if ($report_line[1] == 0) <!--rien ne s'est passé-->
                        <span class="badge badge-primary">Non Traité</span>
                    @elseif($report_line[1] == 1) <!--succès-->
                        <span class="badge badge-success">Succès</span>
                    @else <!--traitement(s) en cours-->
                        <span class="badge badge-danger">Echèc</span>
                    @endif
                </td>
                <td class="text-left">{{ $report_line[2] }}</td>
                <td class="text-center">{{ $report_line[3] }}</td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>
