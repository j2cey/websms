<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsimportStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smsimport_statuses', function (Blueprint $table) {
            $table->id();

            $table->integer('code')->unique()->comment('code du statut de l\'importation');
            $table->string('titre')->comment('titre du statut de l\'importation');
            $table->string('description')->nullable()->comment('desdcription du statut de l\'importation');

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
        Schema::dropIfExists('smsimport_statuses');
    }
}
