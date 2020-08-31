<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmscampaignStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smscampaign_statuses', function (Blueprint $table) {
            $table->id();

            $table->integer('code')->unique()->comment('code du statut de campagne');
            $table->string('titre')->comment('titre du statut de campagne');

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
        Schema::dropIfExists('smscampaign_statuses');
    }
}
