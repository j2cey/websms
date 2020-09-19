<template>
    <div class="info-box bg-success">
        <span class="info-box-icon"><i class="fa fa-server"></i></span>

        <div class="info-box-content">
            <span class="info-box-text font-14"><strong><u>Importation</u></strong></span>

            <p class="info-box-text m-b-30 font-10">Total à importer: <span class="info-box-number">{{ to_import }}</span>
                Succès: <span class="badge badge-primary">{{ import_success }}</span>
                Echecs: <span class="badge badge-danger">{{ import_failed }}</span>
            </p>

            <div class="progress">
                <div class="progress-bar" :style="{ width: import_rate + '%' }"></div>
            </div>
            <span class="progress-description">{{ import_rate }}% Importation traitée</span>
        </div>
        <!-- /.info-box-content -->
    </div>
</template>

<script>
    export default {
        name: "importresult",
        props: {
            smsresult_prop: {},
            campaign_prop: {}
        },
        mounted() {
            console.log('Component importresult mounted.');

            var self = this;
            let channel = Echo.channel('Smsresult-channel');
            channel.listen('.SmsresultEvent', function (data) {
                console.log(data);
                let newresult_obj = data['result'];
                let updcampaign = data['campaign'];
                if (self.campaign.id === updcampaign.id) {
                    self.smsresult = newresult_obj;
                    self.to_import = newresult_obj ? newresult_obj.nb_to_import : 0;
                    self.import_success = newresult_obj ? newresult_obj.nb_import_success : 0;
                    self.import_failed = newresult_obj ? newresult_obj.nb_import_failed : 0;
                    self.import_rate = newresult_obj ? newresult_obj.import_rate : 0;
                }
            });
        },
        created() {
            console.log('Component importresult created.');
        },
        data() {
            return {
                campaign: this.campaign_prop,
                smsresult: this.smsresult_prop,
                to_import: this.smsresult_prop ? this.smsresult_prop.nb_to_import : 0,
                import_success: this.smsresult_prop ? this.smsresult_prop.nb_import_success : 0,
                import_failed: this.smsresult_prop ? this.smsresult_prop.nb_import_failed : 0,
                import_rate: this.smsresult_prop ? this.smsresult_prop.import_rate : 0
            }
        },
        methods: {
            updateResult(newresult) {
                let newresult_obj = JSON.parse(newresult)
                if (this.smsresult.id === newresult_obj.id) {
                    this.smsresult = newresult_obj;
                    this.to_import = newresult_obj ? newresult_obj.nb_to_import : 0;
                    this.import_success = newresult_obj ? newresult_obj.nb_import_success : 0;
                    this.import_failed = newresult_obj ? newresult_obj.nb_import_failed : 0;
                    this.import_rate = newresult_obj ? newresult_obj.import_rate : 0;
                }
            }
        },
        computed: {

        }
    }
</script>

<style scoped>

</style>
