<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsSkirmishesPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_skirmish', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned();
            $table->integer('skirmish_id')->unsigned();
            $table->primary(['campaign_id', 'skirmish_id']);
            $table->timestamps();
        });

        Schema::table('campaign_skirmish', function (Blueprint $table) {
            $table->foreign('campaign_id')->references('id')->on('campaigns');
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
        Schema::dropIfExists('campaign_skirmish');
    }
}
