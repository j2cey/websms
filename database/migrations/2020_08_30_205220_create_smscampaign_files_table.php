<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmscampaignFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smscampaign_files', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->comment('identifiant universel unique');

            $table->foreignId('smscampaign_planning_id')->nullable()
                ->comment('reference de la planification')
                ->constrained()->onDelete('set null');

            $table->string('name')->comment('nom du fichier');
            $table->boolean('imported')->default(false)->comment('determine si le fichier a deja ete importe dans la BD');

            $table->timestamp('importstart_at')->nullable()->comment('date de debut d importation dans la BD');
            $table->timestamp('importend_at')->nullable()->comment('date de fin d importation dans la BD');

            $table->integer('nb_rows')->default(0)->comment('nombre total de lignes');
            $table->integer('nb_rows_imported')->default(0)->comment('nombre total de lignes importees');
            $table->integer('nb_rows_failed')->default(0)->comment('nombre total de lignes echouees');

            $table->integer('row_last_processed')->default(0)->comment('derniere ligne traitée');
            $table->integer('nb_try')->default(0)->comment('nombre de tentative(s) de traitement');
            $table->json('report')->comment('rapport d importation');

            $table->foreignId('smsimport_status_id')->nullable()
                ->comment('Reference du statut d\'importation')
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
        Schema::table('smscampaign_files', function (Blueprint $table) {
            $table->dropForeign(['smscampaign_planning_id']);
            $table->dropForeign(['smsimport_status_id']);
        });
        Schema::dropIfExists('smscampaign_files');
    }
}
