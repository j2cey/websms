@extends('layouts.app')

@section('page')
    Bienvenue
@endsection

@section('css')
    <style type="text/css">
        body{
            /*background-color: #bdc3c7;*/
        }

        thead, tbody { display: block; }

        tbody {
            height: 340px;       /* Just for the demo          */
            overflow-y: auto;    /* Trigger vertical scroll    */
            overflow-x: hidden;  /* Hide the horizontal scroll */
        }
    </style>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="mini-stat clearfix bg-white">
                <span class="mini-stat-icon"><i class="ti-harddrives"></i></span>
                <div class="mini-stat-info text-right text-light">
                    <span class="counter text-white">XXX</span>Consultations du Jour
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="mini-stat clearfix bg-orange">
                <span class="mini-stat-icon"><i class="ti-shopping-cart-full"></i></span>
                <div class="mini-stat-info text-right text-light">
                    <span class="counter text-white">XXX</span> <u>Autorisations</u> <strong>Alimentaire</strong> du Jour
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="mini-stat clearfix bg-info">
                <span class="mini-stat-icon"><i class="ti-heart-broken"></i></span>
                <div class="mini-stat-info text-right text-light">
                    <span class="counter text-white">XXX</span><u>Autorisations</u> <strong>Santé</strong> du Jour
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="mini-stat clearfix bg-success">
                <span class="mini-stat-icon"><i class="ti-alert"></i></span>
                <div class="mini-stat-info text-right text-light">
                    <span class="counter text-white">XXX</span><u>Autorisations</u> <strong>Urgence</strong> du Jour
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-sec m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Répartition des Autorisations de la Journée</h4>
                    <div class="container">

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-sec m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Requêtes & Consultations de la Journée</h4>

                    <div class="container">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-8">

            <div class="card-group">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mt-0 m-b-15 header-title">Top Hebdo Autorisations Sorties Alimentaire</h6>


                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h6 class="mt-0 m-b-15 header-title">Top Hebdo Autorisations Sorties de Santé</h6>



                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h6 class="mt-0 m-b-15 header-title">Top Hebdo Autorisations Sorties Urgence</h6>


                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-4">
            <div class="card card-sec m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Autorisations de la Semaine</h4>

                    <div class="container">




                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-md-6">
            <div class="card card-sec m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Répartition Hebdo des Résultats par Demande</h4>
                    <div class="container">




                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-sec m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Résumé Hebdo des Résultats</h4>

                    <div class="container">




                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
