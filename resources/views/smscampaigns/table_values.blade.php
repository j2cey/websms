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
    @include('smscampaignstatus.status_display', ['status' => $currval->importstatus])
    {{ $currval->smsresult->nb_import_success ?? '0' }} / {{ $currval->smsresult->nb_to_import ?? '0' }}
</td>
<td>
    @include('smscampaignstatus.status_display', ['status' => $currval->sendstatus])
    {{ $currval->smsresult->nb_send_success ?? '0' }} / {{ $currval->smsresult->nb_to_send ?? '0' }}
</td>
