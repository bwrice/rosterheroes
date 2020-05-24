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
            $table->bigInteger('player_game_log_id')->unique()->unsigned();
            $table->integer('essence_cost');
            $table->integer('energy');
            $table->dateTime('disabled_at')->nullable();
            $table->timestamps();
        });

        Schema::table('player_spirits', function (Blueprint $table) {
            $table->foreign('week_id')->references('id')->on('weeks');
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
