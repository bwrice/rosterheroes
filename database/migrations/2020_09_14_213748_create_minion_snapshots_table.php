<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinionSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minion_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->integer('week_id')->unsigned();
            $table->integer('minion_id')->unsigned();
            $table->integer('level');
            $table->integer('combat_position_id')->unsigned();
            $table->integer('enemy_type_id')->unsigned();
            $table->integer('starting_health');
            $table->integer('protection');
            $table->float('block_chance');
            $table->float('fantasy_power');
            $table->integer('experience_reward');
            $table->integer('favor_reward');
            $table->timestamps();
        });

        Schema::table('minion_snapshots', function (Blueprint $table) {
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('minion_id')->references('id')->on('minions');
            $table->foreign('enemy_type_id')->references('id')->on('enemy_types');
            $table->foreign('combat_position_id')->references('id')->on('combat_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minion_snapshots');
    }
}
