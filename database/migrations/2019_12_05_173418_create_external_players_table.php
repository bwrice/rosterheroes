<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_players', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integration_type_id')->unsigned();
            $table->integer('player_id')->unsigned();
            $table->string('external_id');
            $table->unique(['integration_type_id', 'player_id', 'external_id'], 'integration_player_external_unique');
            $table->timestamps();
        });

        Schema::table('external_players', function (Blueprint $table) {
            $table->foreign('integration_type_id')->references('id')->on('stats_integration_types');
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
        Schema::dropIfExists('external_players');
    }
}
