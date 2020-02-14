<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSideQuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('side_quests', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->integer('quest_id')->unsigned();
            $table->integer('side_quest_blueprint_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('side_quests', function (Blueprint $table) {
            $table->foreign('quest_id')->references('id')->on('quests');
            $table->foreign('side_quest_blueprint_id')->references('id')->on('side_quest_blueprints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('side_quests');
    }
}
