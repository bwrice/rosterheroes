<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->string('slug');
            $table->integer('hero_class_id')->unsigned();
            $table->integer('hero_rank_id')->unsigned();
            $table->integer('hero_race_id')->unsigned();
            $table->integer('combat_position_id')->unsigned();
            $table->integer('player_spirit_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('heroes', function (Blueprint $table) {
            $table->foreign('hero_class_id')->references('id')->on('hero_classes');
            $table->foreign('hero_rank_id')->references('id')->on('hero_ranks');
            $table->foreign('hero_race_id')->references('id')->on('hero_races');
            $table->foreign('combat_position_id')->references('id')->on('combat_positions');
            $table->foreign('player_spirit_id')->references('id')->on('player_spirits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heroes');
    }
}
