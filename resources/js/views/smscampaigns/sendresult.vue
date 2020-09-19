<template>
    <div class="info-box bg-warning">
        <span class="info-box-icon"><i class="fa fa-paper-plane"></i></span>

        <div class="info-box-content">
            <span class="info-box-text font-14"><strong><u>Envoi</u></strong></span>

            <p class="m-b-30 font-10">
                Total à envoyer: <span class="info-box-number">{{ to_send }}</span>
                Succès: <span class="badge badge-success">{{ send_success }}</span>
                Echecs: <span class="badge badge-danger">{{ send_failed }}</span>
            </p>

            <div class="progress">
                <div class="progress-bar" :style="{ width: send_rate  + '%'}"></div>
            </div>
            <span class="progress-description">{{ send_rate }}% Envoi traité</span>
        </div>
        <!-- /.info-box-content -->
    </div>
</template>

<script>
    export default {
        name: "sendresult",
        props: {
            smsresult_prop: {}
        },
        mounted() {

        },
        data() {
            return {
                smsresult: this.smsresult_prop,
                to_send: this.smsresult_prop ? this.smsresult_prop.nb_to_send : 0,
                send_success: this.smsresult_prop ? this.smsresult_prop.nb_send_success : 0,
                send_failed: this.smsresult_prop ? this.smsresult_prop.nb_send_failed : 0,
                send_rate: this.smsresult_prop ? this.smsresult_prop.send_rate : 0
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
