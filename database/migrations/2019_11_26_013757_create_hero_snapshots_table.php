<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('hero_id');
            $table->bigInteger('squad_snapshot_id');
            $table->integer('hero_class_id')->unsigned();
            $table->integer('hero_race_id')->unsigned();
            $table->integer('hero_rank_id')->unsigned();
            $table->integer('combat_position_id')->unsigned();
            $table->bigInteger('player_spirit_id')->unsigned()->nullable();
            $table->float('fantasy_power');
            $table->integer('health');
            $table->integer('stamina');
            $table->integer('mana');
            $table->timestamps();
        });

        Schema::table('hero_snapshots', function (Blueprint $table) {
            $table->foreign('hero_id')->references('id')->on('heroes');
            $table->foreign('squad_snapshot_id')->references('id')->on('squad_snapshots');
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
        Schema::dropIfExists('hero_snapshots');
    }
}
