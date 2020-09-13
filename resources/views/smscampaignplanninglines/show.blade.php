@extends('layouts.app')

@section('page')
    Envoi Campagne SMS
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
                    <p class="text-muted m-b-30 font-14">Détails de <code class="highlighter-rouge"><strong>Ligne Envoi de la Campagne {{ ucfirst($smscampaign->titre) }}</strong></code>.</p>

                    <dl class="row">
                        <dt class="col-sm-3">Id</dt>
                        <dd class="col-sm-9">{{ $planningline->id }}</dd>

                        <dt class="col-sm-3">Statut</dt>
                        <dd class="col-sm-9">
                        @if ($planningline->send_processing == 1) <!--en cours de traitement-->
                            <span class="badge badge-primary">En Cours</span>
                        @elseif($planningline->send_processed == 0) <!--Attente Traitement-->
                            <span class="badge badge-default">Attente Traitement</span>
                        @elseif($planningline->send_success == 1) <!--succès-->
                            <span class="badge badge-success">Succès</span>
                        @else <!--traitement(s) en cours-->
                            <span class="badge badge-danger">Echèc</span>
                            @endif
                        </dd>

                        <dt class="col-sm-6">Début Envoi</dt>
                        <dd class="col-sm-6">{{ $planningline->sendingstart_at ? date('d-m-Y H:i:s', strtotime($planningline->sendingstart_at)) : '' }}</dd>

                        <dt class="col-sm-6">Fin Envoi</dt>
                        <dd class="col-sm-6">{{ $planningline->sendingend_at ? date('d-m-Y H:i:s', strtotime($planningline->sendingend_at)) : '' }}</dd>

                        <dt class="col-sm-6">Numero</dt>
                        <dd class="col-sm-6">{{ $planningline->receiver ? $planningline->receiver->mobile : '' }}</dd>

                        <dt class="col-sm-6">Message</dt>
                        <dd class="col-sm-6">{{ $planningline->message }}</dd>

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
