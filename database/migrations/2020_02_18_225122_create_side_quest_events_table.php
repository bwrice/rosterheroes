<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSideQuestEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('side_quest_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('combat_event_type_id')->unsigned();
            $table->bigInteger('side_quest_result_id')->unsigned();
            $table->integer('moment');
            $table->json('data');
            $table->timestamps();
        });

        Schema::table('side_quest_events', function (Blueprint $table) {
            $table->foreign('combat_event_type_id')->references('id')->on('combat_event_types');
            $table->foreign('side_quest_result_id')->references('id')->on('side_quest_results');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('side_quest_events');
    }
}
