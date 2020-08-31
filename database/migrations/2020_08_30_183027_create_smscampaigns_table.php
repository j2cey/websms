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
            $table->boolean('messages_individuels')->default(false)->comment('determine si la campagne a un message individuel par destinataire');

            $table->integer('planning_sending')->default(0)->comment('nombre de planifications en cours');
            $table->integer('planning_done')->default(0)->comment('nombre de planifications effectuées');
            $table->integer('planning_waiting')->default(0)->comment('nombre de planifications en attente de traitement');

            $table->foreignId('smscampaign_status_id')->nullable()
                ->comment('Reference du statut')
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
        });
        Schema::dropIfExists('smscampaigns');
    }
}
