@extends('layouts.app')

@section('page')

@endsection

@section('buttons')
    @include('layouts._button_create', ['create_route' => 'SmscampaignController@create'])
@endsection

@section('css')
    @include('select2.select2_css')
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Liste</h4>
                    <p class="text-muted m-b-30 font-14">Liste des <code class="highlighter-rouge">Campagnes SMS</code> du Système.</p>

                    <div class="row">
                        @include('layouts.message')
                    </div>

                    <!-- Panel de recherche -->
                    <div class="row">
                        @include('layouts.recherche_panel', ['index_route' => 'SmscampaignController@index'])
                    </div>
                    <!-- Fin Panel de recherche -->

                    <div class="row">

                        <table class="table table-hover table-sm">
                            <thead class="thead-default">
                            <tr>
                                <th class="font-weight-bold">#</th>
                                @include('smscampaigns.table_headers')
                                <th class="font-weight-bold">Date Creation</th>
                                <th class="font-weight-bold text-center" colspan="3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($listvalues as $currval)
                                <tr>
                                    <td class="font-weight-bold text-left">{{ $currval->id }}</td>
                                    @include('smscampaigns.table_values', ['currval' => $currval])
                                    <td class="text-left">{{ date('d-m-Y H:i:s', strtotime($currval->created_at)) }}</td>

                                    <!-- ACTIONS -->

                                    <td style="width: 10px;">
                                        <a href="{{ action('SmscampaignController@show', $currval) }}" alt="Détails" title="Details">
                                            <i class="fa fa-eye" style="color:green"></i>
                                        </a>
                                    </td>

                                    <td style="width: 10px;">
                                        <a href="{{ action('SmscampaignController@edit', $currval) }}" alt="Modifer" title="Edit">
                                            <i class="ti-pencil-alt"></i>
                                        </a>
                                    </td>

                                    <td style="width: 10px;">
                                            <a href="#" onclick="if(confirm('Etes-vous sur de vouloir supprimer?')) {event.preventDefault(); document.getElementById('index_destroy-form-{{ $currval->id }}').submit();}">
                                                <i class="ti-trash" style="color:red"></i>
                                            </a>
                                            <form id="index_destroy-form-{{ $currval->id }}" action="{{ action('SmscampaignController@destroy', $currval) }}" method="POST" style="display: none;">
                                                @method('DELETE')
                                                @csrf
                                                <input type="hidden" value="{{ $currval->id }}" name="id">
                                            </form>
                                    </td>

                                </tr>
                            @empty
                            @endforelse
                            <input type="hidden" name="user" id="user" value="">
                            </tbody>
                        </table>

                        {{ $listvalues->appends(request()->input())->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    @include('smscampaigns.importstatus_js')
    @include('smscampaigns.sendstatus_js')
@endsection
