<template>
    <div class="modal fade" id="addUpdateCampaign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" v-if="editing">Modifier Campagne</h5>
                    <h5 class="modal-title" id="exampleModalLabel" v-else>Créer Nouvelle Campagne</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" @submit.prevent="onSubmit" @keydown="campaignForm.errors.clear()">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="titre" class="col-sm-2 col-form-label">Titre</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="titre" name="titre" autocomplete="titre" autofocus placeholder="Titre" v-model="campaignForm.titre">
                                    <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('titre')" v-text="campaignForm.errors.get('titre')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="m_select" class="col-sm-2 col-form-label">Type</label>
                                <div class="col-sm-10">
                                    <multiselect
                                        id="m_select"
                                        v-model="campaignForm.type"
                                        selected.sync="campaign.type"
                                        value=""
                                        :options="campaigntypes"
                                        :searchable="true"
                                        :multiple="false"
                                        label="titre"
                                        track-by="code"
                                        key="code"
                                        placeholder="Type"
                                    >
                                    </multiselect>
                                    <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('smscampaign_type_code')" v-text="campaignForm.errors.get('smscampaign_type_code')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="expediteur" class="col-sm-2 col-form-label">Expéditeur</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="expediteur" name="expediteur" required autocomplete="expediteur" autofocus placeholder="Expéditeur" v-model="campaignForm.expediteur">
                                    <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('expediteur')" v-text="campaignForm.errors.get('expediteur')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="message" class="col-sm-2 col-form-label">Message</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="message" name="message" required autocomplete="message" autofocus placeholder="Message" v-model="campaignForm.message">
                                    <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('message')" v-text="campaignForm.errors.get('message')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="date_planification" class="col-sm-2 col-form-label">Date Planification</label>
                                <div class="col-sm-10">
                                    <div class="input-group date" id="date_planification" data-target-input="nearest" v-model="campaignForm.date_planification">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#date_planification"/>
                                        <div class="input-group-append" data-target="#date_planification" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('date_planification')" v-text="campaignForm.errors.get('date_planification')"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="description" name="description" required autocomplete="description" autofocus placeholder="Description" v-model="campaignForm.description">
                                    <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('description')" v-text="campaignForm.errors.get('description')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="separateur_colonnes" class="col-sm-2 col-form-label">Séparateur Colonnes</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="separateur_colonnes" name="separateur_colonnes" required autocomplete="separateur_colonnes" autofocus placeholder="Séparateur Colonnes" v-model="campaignForm.separateur_colonnes">
                                    <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('separateur_colonnes')" v-text="campaignForm.errors.get('separateur_colonnes')"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="premiere_ligne_entete" name="premiere_ligne_entete" autocomplete="premiere_ligne_entete" autofocus placeholder="En-tete de colonne ?" v-model="campaignForm.premiere_ligne_entete">
                                        <label class="form-check-label" for="premiere_ligne_entete">En-tete de colonne ?</label>
                                        <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('premiere_ligne_entete')" v-text="campaignForm.errors.get('premiere_ligne_entete')"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label for="fichier_destinataires">Custom File</label> -->

                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="fichier_destinataires" name="fichier_destinataires"  ref="fichier_destinataires" @change="handleFileUpload">
                                    <label class="custom-file-label" for="fichier_destinataires">{{ filename }}</label>
                                    <span class="invalid-feedback d-block" role="alert" v-if="campaignForm.errors.has('fichier_destinataires')" v-text="campaignForm.errors.get('fichier_destinataires')"></span>
                                </div>
                            </div>
                            <div class="form-group">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" @click="updateCampagne()" :disabled="!isValidCreateForm" v-if="editing">Enregistrer</button>
                    <button type="button" class="btn btn-primary" @click="createCampagne()" :disabled="!isValidCreateForm" v-else>Créer Campagne</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'

    class Campaign {
        constructor(campaign) {
            this.titre = campaign.titre || ''
            this.type = campaign.type || ''
            this.expediteur = campaign.expediteur || ''
            this.message = campaign.message || ''
            this.date_planification = campaign.date_planification || ''
            this.description = campaign.description || ''
            this.separateur_colonnes = campaign.separateur_colonnes || ''
            this.premiere_ligne_entete = campaign.premiere_ligne_entete || ''
        }
    }
    export default {
        name: "addupdate",
        props: {
            campaigntypes_toselect: {}
        },
        components: { Multiselect },
        mounted() {
            this.$parent.$on('create_new_campaign', () => {

                this.editing = false
                this.campaign = new Campaign({})
                this.campaignForm = new Form(this.campaign)

                $('#addUpdateCampaign').modal()
            })

            this.$parent.$on('edit_campaign', ({ campaign }) => {
                this.editing = true
                this.campaign = new Campaign(campaign)
                this.campaignForm = new Form(this.campaign)
                this.campaignId = campaign.uuid

                $('#addUpdateCampaign').modal()
            })
        },
        created() {
            axios.get('/smscampaigntypes')
                .then(({data}) => this.campaigntypes = data);
        },
        data() {
            return {
                campaigns: [],
                campaigntypes: [],
                selectedFile : null,
                campaign: {},
                campaignForm: new Form(new Campaign({})),
                campaignId: null,
                filename: 'Télécharger un fichier',
                editing: false,
                loading: false
            }
        },
        methods: {
            handleFileUpload(event) {
                this.selectedFile = event.target.files[0];
                this.filename = (typeof this.selectedFile !== 'undefined') ? this.selectedFile.name : 'Télécharger un fichier';
            },
            createCampagne() {
                this.loading = true
                this.setCampaignTypeCode()

                const fd = new FormData();
                fd.append('fichier_destinataires', this.selectedFile);
                this.campaignForm.type = JSON.stringify(this.campaignForm.type)
                this.campaignForm
                    .post('/smscampaigns', fd)
                    .then(newcampaign => {
                        this.loading = false
                        this.$parent.$emit('new_campaign_created', newcampaign)
                        $('#addUpdateCampaign').modal('hide')
                    }).catch(error => {
                        this.loading = false
                    });
            },
            updateCampagne() {
                this.loading = true
                this.setCampaignTypeCode()

                const fd = this.addFileToForm()

                this.campaignForm.type = JSON.stringify(this.campaignForm.type)
                this.campaignForm
                    .put(`/smscampaigns/${this.campaignId}`, fd)
                    .then(updcampaign => {
                        this.loading = false
                        this.$parent.$emit('campaign_updated', updcampaign)
                        $('#addUpdateCampaign').modal('hide')
                    }).catch(error => {
                    this.loading = false
                });
            },
            setCampaignTypeCode() {
                this.campaignForm.type = this.campaignForm.type.code
            },
            addFileToForm() {
                if (typeof this.selectedFile !== 'undefined') {
                    const fd = new FormData();
                    fd.append('fichier_destinataires', this.selectedFile);
                    return fd;
                } else {
                    const fd = undefined;
                    return fd;
                }
            },


            onSubmit(){
                const fd = new FormData();
                fd.append('fichier_destinataires', this.selectedFile);
                this.campaignForm
                    .post('/smscampaigns', fd)
                    .then(campaign => this.campaigns.push(campaign));
            },
            submitListing(file,src) {
                const config = {
                    headers: { "content-type": "multipart/form-data" }
                };

                let formData = new FormData();
                formData.append(src,file, config);

                axios.post("/smscampaigns", this.campaignForm, formData).then(response => {
                    console.log(response.data);
                });
            },
            oldSubmit() {
                this.campaignForm
                    .post('/smscampaigns')
                    .then(campaign => this.campaigns.push(campaign));
            },
            submitOK() {
                const data = new FormData();
                data.append('fichier_destinataires', this.selectedFile);
                const json = JSON.stringify(this.campaignForm);
                data.append('data', json);
                axios.post("/smscampaigns", data);
            }
        },
        computed: {
            isValidCreateForm() {
                return this.campaignForm.titre && this.campaignForm.type && this.campaignForm.expediteur && !this.loading
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
