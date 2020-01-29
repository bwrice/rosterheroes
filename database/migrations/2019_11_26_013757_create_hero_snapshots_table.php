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
            $table->bigInteger('hero_id')->unsigned();
            $table->bigInteger('squad_snapshot_id')->unsigned();
            $table->bigInteger('player_spirit_id')->unsigned();
            $table->unique(['hero_id', 'squad_snapshot_id']);
            $table->json('data');
            $table->timestamps();
        });

        Schema::table('hero_snapshots', function (Blueprint $table) {
            $table->foreign('hero_id')->references('id')->on('heroes');
            $table->foreign('squad_snapshot_id')->references('id')->on('squad_snapshots');
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
