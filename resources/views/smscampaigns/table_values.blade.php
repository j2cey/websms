<td>{{ $currval->titre }}</td>
<td>
    @if ($currval->type->code == 0)
        <span class="badge badge-secondary">
    @elseif($currval->type->code == 1)
                <span class="badge badge-secondary">
    @else
                        <span class="badge badge-secondary">
    @endif
                                    {{ $currval->type->titre }}</span>
</td>
<td>{{ $currval->expediteur }}</td>
<td>{{ $currval->description }}</td>
<td>{{ $currval->message }}</td>
<td>
    @if ($currval->status->code == 0)
        <span class="badge badge-danger">
    @elseif($currval->status->code == 1)
                <span class="badge badge-secondary">
    @elseif($currval->status->code == 2)
                        <span class="badge badge-warning">
    @else
                                <span class="badge badge-success">
    @endif
                                    {{ $currval->status->titre }}</span>
</td>
