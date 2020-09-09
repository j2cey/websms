<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsresultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smsresults', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->comment('identifiant universel unique');

            $table->timestamp('importstart_at')->nullable()->comment('date de debut d importation dans la BD');
            $table->timestamp('importend_at')->nullable()->comment('date de fin d importation dans la BD');

            $table->integer('nb_to_import')->default(0)->comment('nombre total de lignes');
            $table->integer('nb_import_processing')->default(0)->comment('nombre d\'importation(s) en cours');
            $table->integer('nb_import_success')->default(0)->comment('nombre total de lignes importees avec succès');
            $table->integer('nb_import_failed')->default(0)->comment('nombre total de lignes echouees');
            $table->integer('nb_import_processed')->default(0)->comment('nombre d\'importation(s) effectuée(s)');

            $table->timestamp('sendingstart_at')->nullable()->comment('date début de l\'envoi ');
            $table->timestamp('sendingend_at')->nullable()->comment('date fin de l\'envoi ');

            $table->integer('nb_to_send')->default(0)->comment('nombre de sms a traiter');
            $table->integer('nb_send_processing')->default(0)->comment('nombre d\'envoi(s) en cours');
            $table->integer('nb_send_success')->default(0)->comment('nombre de sms envoyés avec succès');
            $table->integer('nb_send_failed')->default(0)->comment('nombre de sms dont l envoie a échoué');
            $table->integer('nb_send_processed')->default(0)->comment('nombre de traitement(s) effectué(s)');

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
        Schema::dropIfExists('smsresults');
    }
}
