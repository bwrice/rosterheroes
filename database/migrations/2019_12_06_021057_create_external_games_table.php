<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integration_type_id')->unsigned();
            $table->integer('game_id')->unsigned();
            $table->string('external_id');
            $table->unique(['integration_type_id', 'game_id', 'external_id']);
            $table->timestamps();
        });

        Schema::table('external_games', function (Blueprint $table) {
            $table->foreign('integration_type_id')->references('id')->on('stats_integration_types');
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
        Schema::dropIfExists('external_games');
    }
}
