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
        Schema::create('player_game_log_stats', function (Blueprint $table) {
            $table->integer('player_game_log_id')->unsigned();
            $table->integer('stat_id')->unsigned();
            $table->float('amount');
            $table->timestamps();
            $table->primary(['player_game_log_id', 'stat_id']);
        });

        Schema::table('player_game_log_stats', function (Blueprint $table) {
            $table->foreign('player_game_log_id')->references('id')->on('player_game_logs');
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
