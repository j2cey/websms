@if ($status->code == 0) <!--aucun élément à traiter-->
<span class="badge badge-default">
@elseif($status->code == 1) <!--traitement(s) en attente-->
<span class="badge badge-primary">
@elseif($status->code == 2) <!--traitement(s) en cours-->
<span class="badge badge-info">
@elseif($status->code == 3) <!--succès traitement(s)-->
<span class="badge badge-success">
@elseif($status->code == 4) <!--traitement(s) effectué(s) avec erreur(s)-->
<span class="badge badge-warning">
@else <!--échec traitement(s)-->
<span class="badge badge-danger">
@endif
{{ $status->titre }}</span>
