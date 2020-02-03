<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinionSideQuestPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minion_side_quest', function (Blueprint $table) {
            $table->integer('minion_id')->unsigned();
            $table->integer('side_quest_id')->unsigned();
            $table->integer('count');
            $table->primary(['minion_id', 'side_quest_id']);
            $table->timestamps();
        });

        Schema::table('minion_side_quest', function (Blueprint $table) {
            $table->foreign('minion_id')->references('id')->on('minions');
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
        Schema::dropIfExists('minion_skirmish');
    }
}
