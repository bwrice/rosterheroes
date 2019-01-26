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
            $table->integer('quest_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('skirmishes', function (Blueprint $table) {
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
        Schema::dropIfExists('skirmishes');
    }
}
