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
                    <form class="form-horizontal" @submit.prevent="onSubmit" @keydown="form.errors.clear()">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="titre" class="col-sm-2 col-form-label">Titre</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="titre" name="titre" autocomplete="titre" autofocus placeholder="Titre" v-model="form.titre">
                                    <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('titre')" v-text="form.errors.get('titre')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="smscampaign_type_code" class="col-sm-2 col-form-label">Type</label>
                                <div class="col-sm-10">
                                    <select class="custom-select" id="smscampaign_type_code" name="smscampaign_type_code" autofocus v-model="form.smscampaign_type_code">
                                        <option v-for="campaigntype in campaigntypes">{{campaigntype.titre}}</option>
                                    </select>
                                    <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('smscampaign_type_code')" v-text="form.errors.get('smscampaign_type_code')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="expediteur" class="col-sm-2 col-form-label">Expéditeur</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="expediteur" name="expediteur" required autocomplete="expediteur" autofocus placeholder="Expéditeur" v-model="form.expediteur">
                                    <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('expediteur')" v-text="form.errors.get('expediteur')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="message" class="col-sm-2 col-form-label">Message</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="message" name="message" required autocomplete="message" autofocus placeholder="Message" v-model="form.message">
                                    <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('message')" v-text="form.errors.get('message')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="date_planification" class="col-sm-2 col-form-label">Date Planification</label>
                                <div class="col-sm-10">
                                    <div class="input-group date" id="date_planification" data-target-input="nearest" v-model="form.date_planification">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#date_planification"/>
                                        <div class="input-group-append" data-target="#date_planification" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('date_planification')" v-text="form.errors.get('date_planification')"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="description" name="description" required autocomplete="description" autofocus placeholder="Description" v-model="form.description">
                                    <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="separateur_colonnes" class="col-sm-2 col-form-label">Séparateur Colonnes</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="separateur_colonnes" name="separateur_colonnes" required autocomplete="separateur_colonnes" autofocus placeholder="Séparateur Colonnes" v-model="form.separateur_colonnes">
                                    <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('separateur_colonnes')" v-text="form.errors.get('separateur_colonnes')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="premiere_ligne_entete" name="premiere_ligne_entete" autocomplete="premiere_ligne_entete" autofocus placeholder="En-tete de colonne ?" v-model="form.premiere_ligne_entete">
                                        <label class="form-check-label" for="premiere_ligne_entete">En-tete de colonne ?</label>
                                        <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('premiere_ligne_entete')" v-text="form.errors.get('premiere_ligne_entete')"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label for="fichier_destinataires">Custom File</label> -->

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="fichier_destinataires" name="fichier_destinataires"  ref="fichier_destinataires" @change="handleFileUpload">
                                    <label class="custom-file-label" for="fichier_destinataires">{{ filename }}</label>
                                    <span class="invalid-feedback d-block" role="alert" v-if="form.errors.has('fichier_destinataires')" v-text="form.errors.get('fichier_destinataires')"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info" :disabled="form.errors.any()">Valider</button>
                            <button class="btn btn-default float-right">Annuler</button>
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
        name: "index",
        data() {
            return {
                campaigntypes: [],
                campaigns: [],
                selectedFile : null,
                form: new Form({
                    'titre': '',
                    'smscampaign_type_code': '',
                    'expediteur': '',
                    'message': '',
                    'date_planification': '',
                    'description': '',
                    'separateur_colonnes': '',
                    'premiere_ligne_entete': ''
                }),
                filename: 'Télécharger un fichier'
            }
        },
        created() {
            axios.get('/smscampaigntypes')
                .then(({data}) => this.campaigntypes = data);
        },
        methods: {
            handleFileUpload(event) {
                //this.form.fichier_destinataires = this.$refs.fichier_destinataires.files[0];
                //this.filename = this.form.fichier_destinataires.name;

                this.selectedFile = event.target.files[0];
                this.filename = this.selectedFile.name;

                console.log(this.form);
            },
            onSubmit(){
                const fd = new FormData();
                fd.append('fichier_destinataires', this.selectedFile);
                this.form
                    .post('/smscampaigns', fd)
                    .then(campaign => this.campaigns.push(campaign));
            },
            submitListing(file,src) {
                const config = {
                    headers: { "content-type": "multipart/form-data" }
                };

                let formData = new FormData();
                formData.append(src,file, config);

                axios.post("/smscampaigns", this.form, formData).then(response => {
                    console.log(response.data);
                });
            },
            oldSubmit() {
                this.form
                    .post('/smscampaigns')
                    .then(campaign => this.campaigns.push(campaign));
            },
            submitOK() {
                const data = new FormData();
                data.append('fichier_destinataires', this.selectedFile);
                const json = JSON.stringify(this.form);
                data.append('data', json);
                axios.post("/smscampaigns", data);
            }
        }
    }
</script>

<style scoped>

</style>
