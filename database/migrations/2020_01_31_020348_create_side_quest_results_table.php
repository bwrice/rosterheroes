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
            $table->bigInteger('squad_id')->unsigned();
            $table->integer('side_quest_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->dateTime('rewards_processed_at')->nullable();
            $table->unique(['squad_id', 'side_quest_id', 'week_id']);
            $table->timestamps();
        });

        Schema::table('side_quest_results', function (Blueprint $table) {
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('side_quest_id')->references('id')->on('side_quests');
            $table->foreign('week_id')->references('id')->on('weeks');
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
