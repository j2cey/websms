<td>{{ $currval->titre }}</td>
<td>
    @if ($currval->type->code == 0)
        <span class="badge badge-pill">
    @elseif($currval->type->code == 1)
                <span class="badge badge-default">
    @else
                        <span class="badge badge-default">
    @endif
                                    {{ $currval->type->titre }}</span>
</td>
<td>{{ $currval->expediteur }}</td>
<td>{{ $currval->description }}</td>
<td>{{ $currval->message }}</td>
<td>
    @if ($currval->status->code == 0) <!--nouveau-->
        <span class="badge badge-default">
    @elseif($currval->status->code == 1) <!--attente importation fichier(s)-->
        <span class="badge badge-primary">
    @elseif($currval->status->code == 2) <!--importation fichier(s) en cours-->
        <span class="badge badge-info">
    @elseif($currval->status->code == 3) <!--succès importation fichier(s)-->
        <span class="badge badge-success">
    @elseif($currval->status->code == 4) <!--fichier(s) importé(s) avec erreur(s)-->
        <span class="badge badge-warning">
    @elseif($currval->status->code == 5) <!--échec importation fichier(s)-->
        <span class="badge badge-danger">
    @elseif($currval->status->code == 6) <!--attente traitement-->
        <span class="badge badge-primary">
    @elseif($currval->status->code == 7) <!--traitement en cours-->
        <span class="badge badge-info">
    @elseif($currval->status->code == 8) <!--succès traitement-->
        <span class="badge badge-success">
    @elseif($currval->status->code == 9) <!--traitement effectué avec erreur(s)-->
        <span class="badge badge-warning">
    @else <!--échec traitement-->
        <span class="badge badge-danger">
    @endif <!--le titre-->
        {{ $currval->status->titre }}</span>
</td>
