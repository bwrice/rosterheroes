<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkirmishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skirmishes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->integer('quest_id')->unsigned();
            $table->integer('skirmish_blueprint_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('skirmishes', function (Blueprint $table) {
            $table->foreign('quest_id')->references('id')->on('quests');
            $table->foreign('skirmish_blueprint_id')->references('id')->on('skirmish_blueprints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skirmishes');
    }
}
