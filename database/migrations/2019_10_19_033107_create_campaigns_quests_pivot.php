<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsQuestsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_quest', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned();
            $table->integer('quest_id')->unsigned();
            $table->primary(['campaign_id', 'quest_id']);
            $table->timestamps();
        });

        Schema::table('campaign_quest', function (Blueprint $table) {
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->foreign('quest_id')->references('id')->on('quests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_quest');
    }
}
