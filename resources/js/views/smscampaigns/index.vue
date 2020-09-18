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
                <!-- SELECT2 EXAMPLE -->
                <div class="card card-default">
                    <div class="card-header">
                        <div class="form-inline float-left">
                            <span class="help-inline pr-1"> Liste des Campagnes </span>
                            <router-link tag="a" to="/smscampaigns/create" class="nav-link">
                                <button class="btn btn-xs btn-primary">Nouvelle</button>
                            </router-link>
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
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Expéditeur</th>
                                    <th>Message</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="campaign in campaigns">
                                    <td>{{campaign.id}}</td>
                                    <td>{{campaign.titre}}</td>
                                    <td>{{campaign.expediteur}}</td>
                                    <td>{{campaign.message}}</td>
                                    <td>{{campaign.description}}</td>
                                </tr>
                            </tbody>
                        </table>
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

    </div>

</template>

<script>
    export default {
        name: "index",
        data() {
            return {
                campaigns: []
            }
        },
        created() {
            axios.get('/smscampaigns')
                .then(({data}) => this.campaigns = data);
            console.log(this.campaigns);
        },
    }
</script>

<style scoped>

</style>
