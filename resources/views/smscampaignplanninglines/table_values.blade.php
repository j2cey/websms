<td>
@if ($currval->send_processing == 1) <!--en cours de traitement-->
    <span class="badge badge-primary">En Cours</span>
@elseif($currval->send_processed == 0) <!--Attente Traitement-->
    <span class="badge badge-default">Attente Traitement</span>
@elseif($currval->send_success == 1) <!--succès-->
    <span class="badge badge-success">Succès</span>
@else <!--traitement(s) en cours-->
    <span class="badge badge-danger">Echèc</span>
@endif
</td>
<td class="text-left">{{ $currval->sendingstart_at ? date('d-m-Y H:i:s', strtotime($currval->sendingstart_at)) : '' }}</td>
<td class="text-left">{{ $currval->sendingend_at ? date('d-m-Y H:i:s', strtotime($currval->sendingend_at)) : '' }}</td>
<td>{{ $currval->receiver ? $currval->receiver->mobile : '' }}</td>
<td>{{ $currval->message }}</td>
