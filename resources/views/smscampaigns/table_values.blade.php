<td>{{ $currval->titre }}</td>
<td>{{ $currval->expediteur }}</td>
<td>{{ $currval->description }}</td>
<td>{{ $currval->message }}</td>
<td>
    @if ($currval->status->code == 0)
        <span class="badge badge-success">
    @elseif($currval->status->code == 1)
                <span class="badge badge-light">
    @elseif($currval->status->code == 2)
                        <span class="badge badge-secondary">
    @else
                                <span class="badge badge-danger">
    @endif
                                    {{ $currval->status->titre }}</span>
</td>
