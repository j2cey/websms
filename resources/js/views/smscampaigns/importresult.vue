<template>
    <div class="info-box bg-success">
        <span class="info-box-icon"><i class="fa fa-server"></i></span>

        <div class="info-box-content">
            <span class="info-box-text font-14"><strong><u>Importation</u></strong></span>

            <p class="info-box-text m-b-30 font-10">Total à importer: <span class="info-box-number">{{ to_import }}</span>
                Succès: <span class="badge badge-success">{{ import_success }}</span>
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
            smsresult_prop: {}
        },
        mounted() {

        },
        data() {
            return {
                smsresult: this.smsresult_prop,
                to_import: this.smsresult_prop ? this.smsresult_prop.nb_to_import : 0,
                import_success: this.smsresult_prop ? this.smsresult_prop.nb_import_success : 0,
                import_failed: this.smsresult_prop ? this.smsresult_prop.nb_import_failed : 0,
                import_rate: this.smsresult_prop ? this.smsresult_prop.import_rate : 0
            }
        },
        methods: {
        },
        computed: {

        }
    }
</script>

<style scoped>

</style>
