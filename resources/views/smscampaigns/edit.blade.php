@extends('layouts.app')

@section('page')
    Campagne SMS
@endsection

@section('css')

@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Modification</h4>
                    <p class="text-muted m-b-30 font-14">Modification de la Campagne <code class="highlighter-rouge"><strong>{{ ucfirst($smscampaign->titre) }}</strong></code>.</p>

                    <form action="{{ action('SmscampaignController@update', $smscampaign) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')

                        @include('smscampaigns.fields')

                        <div class="form-group row">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Valider</button>
                                <button type="reset" class="btn btn-success waves-effect waves-light">Reset</button>
                                <a href="{{ action('SmscampaignController@index') }}" class="btn btn-secondary waves-effect m-l-5">Annuler</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
