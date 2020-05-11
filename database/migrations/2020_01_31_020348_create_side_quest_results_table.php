<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSideQuestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('side_quest_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->bigInteger('campaign_stop_id')->unsigned();
            $table->integer('side_quest_id')->unsigned();
            $table->dateTime('combat_processed_at')->nullable();
            $table->dateTime('rewards_processed_at')->nullable();
            $table->dateTime('side_effects_processed_at')->nullable();
            $table->unique(['campaign_stop_id', 'side_quest_id']);
            $table->timestamps();
        });

        Schema::table('side_quest_results', function (Blueprint $table) {
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
        Schema::dropIfExists('side_quest_results');
    }
}
