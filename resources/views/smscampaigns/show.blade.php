@extends('layouts.app')

@section('page')
    Campagne SMS
@endsection

@section('css')

@endsection

@section('content')

    <div class="row">
        @include('layouts.message')
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Détails</h4>
                    <p class="text-muted m-b-30 font-14">Détails de la Campagne <code class="highlighter-rouge"><strong>{{ ucfirst($smscampaign->titre) }}</strong></code>.</p>

                    <dl class="row">
                        <dt class="col-sm-3">Id</dt>
                        <dd class="col-sm-9">{{ $smscampaign->id }}</dd>

                        <dt class="col-sm-3">Type</dt>
                        <dd class="col-sm-9">{{ $smscampaign->type->titre ?? '' }}</dd>

                        <dt class="col-sm-3">Titre</dt>
                        <dd class="col-sm-9">{{ $smscampaign->titre }}</dd>

                        <dt class="col-sm-3">Expéditeur</dt>
                        <dd class="col-sm-9">{{ $smscampaign->expediteur }}</dd>

                        <dt class="col-sm-3">Message</dt>
                        <dd class="col-sm-9">{{ $smscampaign->message ?? '' }}</dd>

                        <dt class="col-sm-3">Description</dt>
                        <dd class="col-sm-9">{{ $smscampaign->description ?? '' }}</dd>

                        <dt class="col-sm-3">Séeparateur Colonnes</dt>
                        <dd class="col-sm-9">{{ $smscampaign->separateur_colonnes ?? '' }}</dd>

                        <dt class="col-sm-3">Importation</dt>
                        <dd class="col-sm-9">Total à importer: <span class="badge badge-default">{{ $smscampaign->nb_to_import }}</span>, Succès: <span class="badge badge-success">{{ $smscampaign->nb_import_success }}</span>, Echecs: <span class="badge badge-danger">{{ $smscampaign->nb_import_failed }}</span></dd>

                        <dt class="col-sm-3">Envoie</dt>
                        <dd class="col-sm-9">Total à envoyer: <span class="badge badge-default">{{ $smscampaign->nb_to_send }}</span>, Succès: <span class="badge badge-success">{{ $smscampaign->nb_send_success }}</span>, Echecs: <span class="badge badge-danger">{{ $smscampaign->nb_send_failed }}</span></dd>

                    </dl>

                </div>
            </div>
        </div>
    </div>
@endsection
