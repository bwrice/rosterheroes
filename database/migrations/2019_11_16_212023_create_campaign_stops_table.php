<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_stops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->bigInteger('campaign_id')->unsigned();
            $table->integer('quest_id')->unsigned();
            $table->integer('province_id')->unsigned();
            $table->timestamps();
        });


        Schema::table('campaign_stops', function (Blueprint $table) {
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->foreign('quest_id')->references('id')->on('quests');
            $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_stops');
    }
}
