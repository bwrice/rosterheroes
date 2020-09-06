<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSquadSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squad_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->integer('week_id')->unsigned();
            $table->bigInteger('squad_id')->unsigned();
            $table->bigInteger('experience')->unsigned();
            $table->integer('squad_rank_id')->unsigned();
            $table->unique(['week_id', 'squad_id']);
            $table->timestamps();
        });

        Schema::table('squad_snapshots', function (Blueprint $table) {
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('squad_rank_id')->references('id')->on('squad_ranks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squad_snapshots');
    }
}
