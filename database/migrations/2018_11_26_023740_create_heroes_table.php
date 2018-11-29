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
            $table->string('name');
            $table->integer('squad_id')->unsigned();
            $table->integer('hero_class_id')->unsigned();
            $table->integer('hero_race_id')->unsigned();
            $table->integer('hero_rank_id')->unsigned();
            $table->integer('player_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('heroes', function (Blueprint $table) {
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('hero_class_id')->references('id')->on('hero_classes');
            $table->foreign('hero_race_id')->references('id')->on('hero_races');
            $table->foreign('hero_rank_id')->references('id')->on('hero_ranks');
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
