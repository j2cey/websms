<td>{{ $currval->titre }}</td>
<td>
    @if ($currval->type->code == 0)
        <span class="badge badge-default">
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
    @if ($currval->status->code == 0)
        <span class="badge badge-default">
    @elseif($currval->status->code == 1)
                <span class="badge badge-primary">
    @elseif($currval->status->code == 2)
                        <span class="badge badge-secondary">
    @elseif($currval->status->code == 3)
                                <span class="badge badge-secondary">
    @elseif($currval->status->code == 4)
                                        <span class="badge badge-success">
    @elseif($currval->status->code == 5)
                                                <span class="badge badge-warning">
    @else
                                <span class="badge badge-danger">
    @endif
                                    {{ $currval->status->titre }}</span>
</td>
