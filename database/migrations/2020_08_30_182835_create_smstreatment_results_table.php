<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmstreatmentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smstreatment_results', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->comment('identifiant universel unique');

            $table->integer('code')->unique()->comment('code du type de campagne');
            $table->string('titre')->comment('titre du résultat');
            $table->string('description')->nullable()->comment('description du résultat');

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
        Schema::dropIfExists('smstreatment_results');
    }
}
