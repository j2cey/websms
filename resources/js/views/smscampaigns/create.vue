<template>

    <div>

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Campagne SMS</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item">
                                <router-link tag="a" :to="{ name: 'smscampaigns.index' }">Campagnes SMS</router-link>
                            </li>
                            <li class="breadcrumb-item active">Nouvelle</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Horizontal Form -->
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Nouvelle Campagne SMS</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="titre" class="col-sm-2 col-form-label">Titre</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="titre" placeholder="Titre">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="smscampaign_type_code" class="col-sm-2 col-form-label">Type</label>
                                <div class="col-sm-10">
                                    <select class="custom-select" id="smscampaign_type_code">
                                        <option>option 1</option>
                                        <option>option 2</option>
                                        <option>option 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="expediteur" class="col-sm-2 col-form-label">Expéditeur</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="expediteur" placeholder="Expéditeur">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="message" class="col-sm-2 col-form-label">Message</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="message" placeholder="Message">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="date_planification" class="col-sm-2 col-form-label">Date Planification</label>
                                <div class="col-sm-10">
                                    <div class="input-group date" id="date_planification" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#date_planification"/>
                                        <div class="input-group-append" data-target="#date_planification" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="description" placeholder="Description">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="separateur_colonnes" class="col-sm-2 col-form-label">Séparateur Colonnes</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="separateur_colonnes" placeholder="Séparateur Colonnes">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="premiere_ligne_entete">
                                        <label class="form-check-label" for="premiere_ligne_entete">En-tete de colonne ?</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label for="fichier_destinataires">Custom File</label> -->

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="fichier_destinataires">
                                    <label class="custom-file-label" for="fichier_destinataires">Fichier Destinataires/Message</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-secondary">Valider</button>
                            <button type="submit" class="btn btn-default float-right">Annuler</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
                <!-- /.card -->

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

    </div>

</template>

<script>
    export default {
        name: "index"
    }
</script>

<style scoped>

</style>
