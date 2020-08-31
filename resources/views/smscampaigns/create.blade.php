@extends('layouts.app')

@section('content')

    <div class="row">
        @include('layouts.message')
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Nouveau</h4>
                    <p class="text-muted m-b-30 font-14">Création d'une Nouvelle <code class="highlighter-rouge">{{ ucfirst('Campagne SMS') }}</code> .</p>

                    <form action="{{ action('SmscampaignController@store') }}" method="POST" enctype="multipart/form-data">

                        @include('smscampaigns.fields')

                        <div class="form-group row">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Valider</button>
                                <a href="{{ action('SmscampaignController@index') }}" class="btn btn-secondary waves-effect m-l-5">Annuler</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
