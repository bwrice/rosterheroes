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
            $table->integer('weekly_game_player_id')->unsigned()->nullable();
            $table->smallInteger('salary')->nullable();
            $table->timestamps();
        });

        Schema::table('heroes', function (Blueprint $table) {
            $table->foreign('hero_class_id')->references('id')->on('hero_classes');
            $table->foreign('hero_rank_id')->references('id')->on('hero_ranks');
            $table->foreign('weekly_game_player_id')->references('id')->on('game_players');
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
