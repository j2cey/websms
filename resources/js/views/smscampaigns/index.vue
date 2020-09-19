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
                            <li class="breadcrumb-item active">Campagne SMS</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="card card-default">
                    <div class="card-header">
                        <div class="form-inline float-left">
                            <span class="help-inline pr-1"> Liste des Campagnes </span>
                            <button class="btn btn-xs btn-primary" @click="createNewCampaign()">Nouvelle</button>
                        </div>

                        <div class="card-tools">

                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="campaignlist">

                            <div class="card card-widget" v-for="(campaign, index) in campaigns" v-if="campaigns">
                                <div class="card-header">
                                    <div class="user-block">
                                        <span class="username text-primary">{{ campaign.titre }}</span>
                                        <span class="description">{{ campaign.type.titre }}</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-toggle="tooltip" @click="editCampaign(campaign)">
                                            <i class="fa fa-pencil-alt"></i></button>
                                        <button type="button" class="btn btn-tool" data-toggle="collapse" data-parent="#campaignlist" :href="'#collapse-campaigns-'+index"><i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" @click="deleteCampaign(campaign.uuid, index)"><i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div :id="'collapse-campaigns-'+index" class="panel-collapse collapse in">
                                    <div class="card-body" >


                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-12">
                                                <div class="info-box">

                                                    <div class="info-box-content">
                                                        <dt>Expéditeur</dt>
                                                        <dd>{{ campaign.expediteur }}</dd>
                                                        <dt>Message</dt>
                                                        <dd>{{ campaign.message }}</dd>
                                                        <dd class="col-sm-8 offset-sm-4"></dd>
                                                        <dt>Description</dt>
                                                        <dd>{{ campaign.description }}</dd>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <ImportResult :smsresult_prop="campaign.smsresult"></ImportResult>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <SendResult :smsresult_prop="campaign.smsresult"></SendResult>
                                            </div>
                                            <!-- /.col -->

                                        </div>

                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>

                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        .
                    </div>
                </div>
                <!-- /.card -->

                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <AddUpdateCampaign :campaigntypes_toselect="campaigntypes"></AddUpdateCampaign>
    </div>

</template>

<script>
    import AddUpdateCampaign from './addupdate'
    import ImportResult from './importresult'
    import SendResult from './sendresult'
    export default {
        name: "index",
        mounted() {
            this.$on('new_campaign_created', (campaign) => {
                window.noty({
                    message: 'Campagne créée avec succès',
                    type: 'success'
                })
                // insert la nouvelle campaign dans le tableau des campaigns
                this.campaigns.push(campaign)
            })

            this.$on('campaign_updated', (campaign) => {
                // on récupère l'index de la campaign modifiée
                let campaignIndex = this.campaigns.findIndex(c => {
                    return campaign.id == c.id
                })

                this.campaigns.splice(campaignIndex, 1, campaign)
                window.noty({
                    message: 'Campagne modifiée avec succès',
                    type: 'success'
                })

            })
        },
        components: {
            AddUpdateCampaign, ImportResult, SendResult
        },
        data() {
            return {
                campaigntypes: [],
                campaigns: []
            }
        },
        created() {
            axios.get('/smscampaigntypes')
                .then(({data}) => this.campaigntypes = data);

            axios.get('/smscampaigns')
                .then(({data}) => this.campaigns = data);
        },
        methods: {
            createNewCampaign() {
                this.$emit('create_new_campaign')
            },
            editCampaign(campaign) {
                this.$emit('edit_campaign', { campaign })
            },
            deleteCampaign(id, key) {
                if(confirm('Voulez-vous vraiment supprimer ?')) {
                    axios.delete(`/smscampaigns/${id}`)
                        .then(resp => {
                            this.campaigns.splice(key, 1)
                            window.noty({
                                message: 'Campagne supprimée avec succès',
                                type: 'success'
                            })
                        }).catch(error => {
                        window.handleErrors(error)
                    })
                }
            },
            getSendRate(result) {
                return result ? result.send_rate : 0
            }
        }
    }
</script>

<style scoped>

</style>
