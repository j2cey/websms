<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmscampaignPlanningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smscampaign_plannings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->comment('identifiant universel unique');

            $table->foreignId('smscampaign_id')->nullable()
                ->comment('reference de la campagne')
                ->constrained()->onDelete('set null');

            $table->boolean('current')->nullable(1)->comment('détermine si cette planification fait partie des planification en cours ou dernierement traitées');

            $table->timestamp('plan_at')->nullable()->comment('date planifie du traitement');
            $table->timestamp('plandone_at')->nullable()->comment('date effective de la planification du traitement');

            $table->timestamp('importstart_at')->nullable()->comment('date de debut d importation dans la BD');
            $table->timestamp('importend_at')->nullable()->comment('date de fin d importation dans la BD');

            $table->integer('nb_to_import')->default(0)->comment('nombre total de lignes');
            $table->integer('nb_import_success')->default(0)->comment('nombre total de lignes importees');
            $table->integer('nb_import_failed')->default(0)->comment('nombre total de lignes echouees');

            $table->timestamp('sendingstart_at')->nullable()->comment('date début de l\'envoi ');
            $table->timestamp('sendingend_at')->nullable()->comment('date fin de l\'envoi ');

            $table->integer('nb_to_send')->default(0)->comment('nombre de sms a traiter');
            $table->integer('nb_send_processing')->default(false)->comment('nombre d\'envoi en cours');
            $table->integer('nb_send_success')->default(0)->comment('nombre de sms envoyés avec succès');
            $table->integer('nb_send_failed')->default(0)->comment('nombre de sms dont l envoie a échoué');
            $table->integer('nb_send_processed')->default(0)->comment('nombre de traitement(s) effectué(s)');

            $table->foreignId('smsimport_status_id')->nullable()
                ->comment('Reference du statut d\'importation')
                ->constrained()->onDelete('set null');

            $table->foreignId('smssend_status_id')->nullable()
                ->comment('Reference du statut de l\'envoie')
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
        Schema::table('smscampaign_plannings', function (Blueprint $table) {
            $table->dropForeign(['smsimport_status_id']);
            $table->dropForeign(['smssend_status_id']);
            $table->dropForeign(['smscampaign_id']);
        });
        Schema::dropIfExists('smscampaign_plannings');
    }
}
