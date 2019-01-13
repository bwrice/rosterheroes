<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePlayerPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_player', function (Blueprint $table) {
            $table->integer('game_id')->unsigned();
            $table->integer('player_id')->unsigned();
            $table->primary(['game_id', 'player_id']);
        });

        Schema::table('game_player', function (Blueprint $table) {
            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('player_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_player', function (Blueprint $table) {
            //
        });
    }
}
