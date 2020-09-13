<td>@include('smscampaignstatus.status_display', ['status' => $currval->importstatus])</td>
<td class="text-left">{{ $currval->importstart_at ? date('d-m-Y H:i:s', strtotime($currval->importstart_at)) : '' }}</td>
<td class="text-left">{{ $currval->importend_at ? date('d-m-Y H:i:s', strtotime($currval->importend_at)) : '' }}</td>
<td>{{ $currval->nb_rows }}</td>
<td>{{ $currval->nb_rows_success }}</td>
<td>{{ $currval->nb_rows_failed }}</td>
<td>{{ $currval->nb_rows_processing }}</td>
<td>{{ $currval->nb_rows_processed }}</td>
