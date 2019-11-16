<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerSpiritsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_spirits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->integer('week_id')->unsigned();
            $table->integer('player_id')->unsigned();
            $table->integer('game_id')->unsigned();
            $table->bigInteger('player_game_log_id')->unsigned()->nullable();
            $table->integer('essence_cost');
            $table->integer('energy');
            $table->timestamps();
        });

        Schema::table('player_spirits', function (Blueprint $table) {
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('game_id')->references('id')->on('games');
            $table->foreign('player_game_log_id')->references('id')->on('player_game_logs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weekly_game_players');
    }
}
