<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignStopSkirmishPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_stop_skirmish', function (Blueprint $table) {
            $table->bigInteger('stop_id')->unsigned();
            $table->integer('skirmish_id')->unsigned();
            $table->primary(['stop_id', 'skirmish_id']);
            $table->timestamps();
        });

        Schema::table('campaign_stop_skirmish', function (Blueprint $table) {
            $table->foreign('stop_id')->references('id')->on('campaign_stops');
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
        Schema::dropIfExists('campaign_stop_skirmish');
    }
}
