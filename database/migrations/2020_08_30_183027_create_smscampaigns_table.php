<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmscampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smscampaigns', function (Blueprint $table) {
            $table->id();

            $table->string('titre')->comment('titre de la campagne');
            $table->string('expediteur')->comment('nom de l expediteur de la campagne');
            $table->string('description')->nullable()->comment('description de la campagne');
            $table->string('message')->nullable()->comment('message de la campagne');
            $table->string('separateur_colonnes')->nullable()->comment('separateur de colonnes dans le fichier');

            $table->timestamp('importstart_at')->nullable()->comment('date de debut d importation des fichiers de la campagne');
            $table->timestamp('importend_at')->nullable()->comment('date de debut d importation des fichiers de la campagne');

            $table->integer('nb_to_import')->default(0)->comment('nombre de lignes a importer');
            $table->integer('nb_import_success')->default(0)->comment('nombre de lignes importées avec succès');
            $table->integer('nb_import_failed')->default(0)->comment('nombre de lignes dont l importation a échouée');

            $table->integer('planning_sending')->default(0)->comment('nombre de planifications en cours');
            $table->integer('planning_done')->default(0)->comment('nombre de planifications effectuées');
            $table->integer('planning_waiting')->default(0)->comment('nombre de planifications en attente de traitement');

            $table->timestamp('sendingstart_at')->nullable()->comment('date début de l\'envoi des SMS de la campagne');
            $table->timestamp('sendingend_at')->nullable()->comment('date fin de l\'envoi des SMS de la campagne');

            $table->integer('nb_to_send')->default(0)->comment('nombre de sms a traiter');
            $table->integer('nb_send_success')->default(0)->comment('nombre de sms envoyés avec succès');
            $table->integer('nb_send_failed')->default(0)->comment('nombre de sms dont l envoie a échoué');

            $table->foreignId('smscampaign_status_id')->nullable()
                ->comment('Reference du statut')
                ->constrained()->onDelete('set null');

            $table->foreignId('smscampaign_type_id')->nullable()
                ->comment('Reference du type')
                ->constrained()->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smscampaigns', function (Blueprint $table) {
            $table->dropForeign(['smscampaign_status_id']);
            $table->dropForeign(['smscampaign_type_id']);
        });
        Schema::dropIfExists('smscampaigns');
    }
}
