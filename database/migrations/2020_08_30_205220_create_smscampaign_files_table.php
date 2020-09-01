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

            $table->foreignId('smscampaign_id')->nullable()
                ->comment('reference de la campagne')
                ->constrained()->onDelete('set null');

            $table->string('name')->comment('nom du fichier');
            $table->boolean('imported')->default(false)->comment('determine si le fichier a deja ete importe dans la BD');
            $table->timestamp('imported_at')->nullable()->comment('date de l importation dans la BD');

            $table->integer('nb_rows')->default(0)->comment('nombre total de lignes');
            $table->integer('nb_rows_imported')->default(0)->comment('nombre total de lignes importees');
            $table->integer('nb_rows_failed')->default(0)->comment('nombre total de lignes echouees');

            $table->string('import_report')->comment('rapport d importation');

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
        Schema::table('smscampaign_files', function (Blueprint $table) {
            $table->dropForeign(['smscampaign_id']);
            $table->dropForeign(['smscampaign_status_id']);
        });
        Schema::dropIfExists('smscampaign_files');
    }
}
