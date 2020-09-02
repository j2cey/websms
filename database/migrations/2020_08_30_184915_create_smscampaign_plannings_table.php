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

            $table->integer('stat_all')->default(0)->comment('nombre total de traitements à effectuer');
            $table->integer('stat_sending')->default(0)->comment('nombre de traitement en cours');
            $table->integer('stat_success')->default(0)->comment('nombre de succès');
            $table->integer('stat_failed')->default(0)->comment('nombre d\' échecs');
            $table->integer('stat_done')->default(0)->comment('nombre de traitement effectués');

            $table->foreignId('smscampaign_status_id')->nullable()
                ->comment('Reference Statut')
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
            $table->dropForeign(['smscampaign_id']);
            $table->dropForeign(['smscampaign_status_id']);
        });
        Schema::dropIfExists('smscampaign_plannings');
    }
}
