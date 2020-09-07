<a class="btn btn-outline-danger waves-effect waves-light btn-sm"  href="#" onclick="if(confirm('Etes-vous sur de vouloir supprimer?')) {event.preventDefault() ; document.getElementById('buttondestroy-form').submit();}">
    Supprimer
</a>

<form id="buttondestroy-form" action="{{ action($destroy_route, $model) }}" method="POST" style="display: none;">
    @method('DELETE')
    @csrf
</form>
