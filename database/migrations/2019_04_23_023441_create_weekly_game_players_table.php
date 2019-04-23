<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklyGamePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_game_players', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('week_id')->unsigned();
            $table->integer('game_player_id')->unsigned();
            $table->smallInteger('salary');
            $table->timestamps();
        });

        Schema::table('weekly_game_players', function (Blueprint $table) {
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('game_player_id')->references('id')->on('game_players');
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
