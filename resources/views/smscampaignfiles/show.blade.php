@extends('layouts.app')

@section('page')
    Fichier Campagne SMS
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
                    <p class="text-muted m-b-30 font-14">Détails de <code class="highlighter-rouge"><strong>Fichier de la Campagne {{ ucfirst($smscampaign->titre) }}</strong></code>.</p>

                    <dl class="row">
                        <dt class="col-sm-3">Id</dt>
                        <dd class="col-sm-9">{{ $smscampaignfile->id }}</dd>

                        <dt class="col-sm-3">Statut</dt>
                        <dd class="col-sm-9">@include('smscampaignstatus.status_display', ['status' => $smscampaignfile->importstatus])</dd>

                        <dt class="col-sm-6">Début Importation</dt>
                        <dd class="col-sm-6">{{ $smscampaignfile->importstart_at ? date('d-m-Y H:i:s', strtotime($smscampaignfile->importstart_at)) : '' }}</dd>

                        <dt class="col-sm-6">Fin Importation</dt>
                        <dd class="col-sm-6">{{ $smscampaignfile->importend_at ? date('d-m-Y H:i:s', strtotime($smscampaignfile->importend_at)) : '' }}</dd>

                        <dt class="col-sm-6">Total Lignes</dt>
                        <dd class="col-sm-6">{{ $smscampaignfile->nb_rows }}</dd>

                        <dt class="col-sm-6">Lignes Succès</dt>
                        <dd class="col-sm-6">{{ $smscampaignfile->nb_rows_success }}</dd>

                        <dt class="col-sm-6">Lignes Echecs</dt>
                        <dd class="col-sm-6">{{ $smscampaignfile->nb_rows_failed }}</dd>

                        <dt class="col-sm-6">Lignes en cours de Traitement</dt>
                        <dd class="col-sm-6">{{ $smscampaignfile->nb_rows_processing }}</dd>

                        <dt class="col-sm-6">Total Lignes Traitées</dt>
                        <dd class="col-sm-6">{{ $smscampaignfile->nb_rows_processed }}</dd>

                    </dl>

                    <div class="">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $smscampaignfile->getImportPercentage() . '%' }};" aria-valuenow="{{ $smscampaignfile->getImportPercentage() }}" aria-valuemin="0" aria-valuemax="100">{{ $smscampaignfile->getImportPercentage() }}%</div>
                        </div>
                    </div>

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
                                    <a alt="Liste Importations" title="Rapport de Traitement">
                                        Rapport Traitement
                                        <i class="fa fa-leanpub"></i>
                                    </a>
                                </button>
                            </h6>
                            <hr />

                            @include('smsreports.report_display', ['report' => $report])

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
