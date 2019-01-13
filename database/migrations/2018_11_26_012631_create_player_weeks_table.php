<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_weeks', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('player_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->smallInteger('initial_salary');
            $table->smallInteger('salary');
            $table->timestamps();
        });

        Schema::table('player_weeks', function (Blueprint $table) {
            $table->foreign('player_id')->references('id')->on('players');
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
        Schema::dropIfExists('player_weeks');
    }
}
