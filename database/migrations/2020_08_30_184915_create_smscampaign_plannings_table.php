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

            $table->foreignId('smsimport_status_id')->nullable()
                ->comment('Reference du statut d\'importation')
                ->constrained()->onDelete('set null');

            $table->foreignId('smssend_status_id')->nullable()
                ->comment('Reference du statut de l\'envoie')
                ->constrained()->onDelete('set null');

            $table->foreignId('smsresult_id')->nullable()
                ->comment('Reference du résultat du traitement SMS global')
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
            $table->dropForeign(['smsresult_id']);
            $table->dropForeign(['smscampaign_id']);
        });
        Schema::dropIfExists('smscampaign_plannings');
    }
}
