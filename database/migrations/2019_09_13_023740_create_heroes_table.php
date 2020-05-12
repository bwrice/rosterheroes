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
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->string('slug');
            $table->bigInteger('squad_id')->unsigned();
            $table->integer('hero_class_id')->unsigned();
            $table->integer('hero_rank_id')->unsigned();
            $table->integer('hero_race_id')->unsigned();
            $table->integer('combat_position_id')->unsigned();
            $table->bigInteger('player_spirit_id')->unsigned()->nullable();
            $table->bigInteger('damage_dealt')->unsigned()->default(0);
            $table->bigInteger('minion_damage_dealt')->unsigned()->default(0);
            $table->bigInteger('titan_damage_dealt')->unsigned()->default(0);
            $table->bigInteger('side_quest_damage_dealt')->unsigned()->default(0);
            $table->bigInteger('quest_damage_dealt')->unsigned()->default(0);
            $table->bigInteger('damage_taken')->unsigned()->default(0);
            $table->bigInteger('side_quest_damage_taken')->unsigned()->default(0);
            $table->bigInteger('minion_damage_taken')->unsigned()->default(0);
            $table->bigInteger('titan_damage_taken')->unsigned()->default(0);
            $table->bigInteger('minion_deaths')->unsigned()->default(0);
            $table->bigInteger('titan_deaths')->unsigned()->default(0);
            $table->bigInteger('combat_deaths')->unsigned()->default(0);
            $table->bigInteger('side_quest_deaths')->unsigned()->default(0);
            $table->bigInteger('quest_deaths')->unsigned()->default(0);
            $table->bigInteger('attacks_blocked')->unsigned()->default(0);
            $table->bigInteger('minion_kills')->unsigned()->default(0);
            $table->bigInteger('titan_kills')->unsigned()->default(0);
            $table->bigInteger('side_quest_kills')->unsigned()->default(0);
            $table->bigInteger('quest_kills')->unsigned()->default(0);
            $table->bigInteger('combat_kills')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::table('heroes', function (Blueprint $table) {
            $table->foreign('squad_id')->references('id')->on('squads');
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
