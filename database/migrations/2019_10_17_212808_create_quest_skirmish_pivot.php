<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestSkirmishPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_skirmish', function (Blueprint $table) {
            $table->integer('quest_id')->unsigned();
            $table->integer('skirmish_id')->unsigned();
            $table->primary(['quest_id', 'skirmish_id']);
            $table->timestamps();
        });

        Schema::table('quest_skirmish', function (Blueprint $table) {
            $table->foreign('quest_id')->references('id')->on('quests');
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
        Schema::dropIfExists('quest_skirmish');
    }
}
