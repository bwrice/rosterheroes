<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('player_game_log_id')->unsigned();
            $table->integer('stat_type_id')->unsigned();
            $table->float('amount');
            $table->timestamps();
        });

        Schema::table('player_stats', function (Blueprint $table) {
            $table->foreign('player_game_log_id')->references('id')->on('player_game_logs');
            $table->foreign('stat_type_id')->references('id')->on('stat_types');
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
