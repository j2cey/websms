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
        <div class="col-md-6" style="width: 50%">
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

                    </dl>

                </div>
            </div>
        </div>

        <div class="col-md-6" style="width: 50%">

            <div class="row">
                <div class="col">
                    <div class="card m-b-30">
                        <div class="card-body">

                            <h6 class="mt-0 font-12">
                                <button type="button" class="btn waves-effect btn-sm">
                                    <a href="#" alt="Liste Importations" title="Liste Importations">
                                        Importation
                                        <i class="fa fa-file-code-o"></i>
                                    </a>
                                </button>
                            </h6>
                            <hr />
                            <p class="text-muted m-b-30 font-10">Total à importer: <span class="badge badge-default">{{ $smscampaign->smsresult ? $smscampaign->smsresult->nb_to_import : 0 }}</span>
                                .
                                Succès: <span class="badge badge-success">{{ $smscampaign->smsresult ? $smscampaign->smsresult->nb_import_success : 0 }}</span>
                                .
                                Echecs: <span class="badge badge-danger">{{ $smscampaign->smsresult ? $smscampaign->smsresult->nb_import_failed : 0 }}</span>
                            </p>

                            <div class="">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $smscampaign->getImportPercentage() . '%' }};" aria-valuenow="{{ $smscampaign->getImportPercentage() }}" aria-valuemin="0" aria-valuemax="100">{{ $smscampaign->getImportPercentage() }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card m-b-30">
                        <div class="card-body">

                            <h6 class="mt-0 font-12">
                                <button type="button" class="btn waves-effect btn-sm">
                                    <a href="#" alt="Liste Importations" title="Liste Importations">
                                        Envoie <i class="fa fa-paper-plane-o"></i></a>
                                </button>
                            </h6>
                            <hr />
                            <p class="text-muted m-b-30 font-10">
                                Total à envoyer: <span class="badge badge-default">{{ $smscampaign->smsresult ? $smscampaign->smsresult->nb_to_send : 0 }}</span>
                                .
                                Succès: <span class="badge badge-success">{{ $smscampaign->smsresult ? $smscampaign->smsresult->nb_send_success : 0 }}</span>
                                .
                                Echecs: <span class="badge badge-danger">{{ $smscampaign->smsresult ? $smscampaign->smsresult->nb_send_failed : 0 }}</span>
                            </p>

                            <div class="">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $smscampaign->getSendPercentage() . '%' }};" aria-valuenow="{{ $smscampaign->getSendPercentage() }}" aria-valuemin="0" aria-valuemax="100">{{ $smscampaign->getSendPercentage() }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
