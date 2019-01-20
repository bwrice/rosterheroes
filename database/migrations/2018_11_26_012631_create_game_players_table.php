<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_players', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('player_id')->unsigned();
            $table->integer('game_id')->unsigned();
            $table->smallInteger('initial_salary');
            $table->smallInteger('salary');
            $table->timestamps();
        });

        Schema::table('game_players', function (Blueprint $table) {
            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('game_id')->references('id')->on('games');
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
