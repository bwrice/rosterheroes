<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePlayerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_game_stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('game_player_id')->unsigned();
            $table->integer('stat_id')->unsigned();
            $table->float('amount');
            $table->timestamps();
        });

        Schema::table('player_game_stats', function (Blueprint $table) {
            $table->foreign('game_player_id')->references('id')->on('game_players');
            $table->foreign('stat_id')->references('id')->on('stats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_stats');
    }
}
