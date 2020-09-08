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
            $table->uuid('uuid');
            $table->bigInteger('squad_snapshot_id')->unsigned();
            $table->bigInteger('hero_id')->unsigned();
            $table->bigInteger('player_spirit_id')->unsigned()->nullable();
            $table->bigInteger('combat_position_id')->unsigned();
            $table->integer('health')->unsigned();
            $table->integer('stamina')->unsigned();
            $table->integer('mana')->unsigned();
            $table->integer('protection');
            $table->float('block_chance');
            $table->unique(['squad_snapshot_id', 'hero_id']);
            $table->timestamps();
        });

        Schema::table('hero_snapshots', function (Blueprint $table) {
            $table->foreign('squad_snapshot_id')->references('id')->on('squad_snapshots');
            $table->foreign('hero_id')->references('id')->on('heroes');
            $table->foreign('player_spirit_id')->references('id')->on('player_spirits');
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
        Schema::dropIfExists('hero_snapshots');
    }
}
