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
    @include('smscampaigns.status_display', ['status' => $currval->importstatus])
    {{ $currval->nb_import_success }} / {{ $currval->nb_to_import }}
</td>
<td>
    @include('smscampaigns.status_display', ['status' => $currval->sendstatus])
    {{ $currval->nb_send_success }} / {{ $currval->nb_to_send }}
</td>
