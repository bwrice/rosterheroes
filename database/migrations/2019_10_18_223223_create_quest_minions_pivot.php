<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestMinionsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minion_quest', function (Blueprint $table) {
            $table->integer('minion_id')->unsigned();
            $table->integer('quest_id')->unsigned();
            $table->smallInteger('weight')->unsigned();
            $table->primary(['minion_id', 'quest_id']);
            $table->timestamps();
        });

        Schema::table('minion_quest', function (Blueprint $table) {
            $table->foreign('minion_id')->references('id')->on('minions');
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
        Schema::dropIfExists('minion_quest');
    }
}
