<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerPositionPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_position', function (Blueprint $table) {
            $table->integer('player_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->primary(['player_id', 'position_id']);
            $table->timestamps();
        });


        Schema::table('player_position', function (Blueprint $table) {
            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('position_id')->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_position');
    }
}
