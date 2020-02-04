<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignStopSideQuestPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_stop_side_quest', function (Blueprint $table) {
            $table->bigInteger('campaign_stop_id')->unsigned();
            $table->integer('side_quest_id')->unsigned();
            $table->primary(['campaign_stop_id', 'side_quest_id'], 'stop_side_quest_primary');
            $table->timestamps();
        });

        Schema::table('campaign_stop_side_quest', function (Blueprint $table) {
            $table->foreign('campaign_stop_id')->references('id')->on('campaign_stops');
            $table->foreign('side_quest_id')->references('id')->on('side_quests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_stop_side_quest');
    }
}
