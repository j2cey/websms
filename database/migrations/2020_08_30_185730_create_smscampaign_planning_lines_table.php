<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmscampaignPlanningLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smscampaign_planning_lines', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->comment('identifiant universel unique');

            $table->foreignId('smscampaign_planning_id')->nullable()
                ->comment('reference de la planification')
                ->constrained()->onDelete('set null');

            $table->foreignId('smscampaign_receiver_id')->nullable()
                ->comment('reference du destinataire')
                ->constrained()->onDelete('set null');

            $table->string('message')->comment('message a envoyer');

            $table->timestamp('sendingstart_at')->nullable()->comment('date début de l\'envoi ');
            $table->timestamp('sendingend_at')->nullable()->comment('date fin de l\'envoi ');
            $table->boolean('send_processing')->default(false)->comment('determine si l\'envoi est en cours');
            $table->boolean('send_success')->default(false)->comment('determine si l\'envoi est un succès');
            $table->boolean('send_processed')->default(false)->comment('determine si l\'envoi a été traité');
            $table->integer('nb_try')->default(0)->comment('nombre de tentative(s) de traitement');
            $table->json('report')->comment('rapport de traitement');

            $table->timestamp('suspended_at')->nullable()->comment('date de suspension le cas échéant');

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
        Schema::table('smscampaign_planning_lines', function (Blueprint $table) {
            $table->dropForeign(['smscampaign_planning_id']);
            $table->dropForeign(['smscampaign_receiver_id']);
        });
        Schema::dropIfExists('smscampaign_planning_lines');
    }
}
