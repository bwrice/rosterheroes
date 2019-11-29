<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignStopSkirmishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_stop_skirmishes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->bigInteger('campaign_stop_id')->unsigned();
            $table->integer('skirmish_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('campaign_stop_skirmishes', function (Blueprint $table) {
            $table->foreign('campaign_stop_id')->references('id')->on('campaign_stops');
            $table->foreign('skirmish_id')->references('id')->on('skirmishes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_stop_skirmishes');
    }
}
