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
            $table->bigInteger('squad_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->unique(['squad_id', 'week_id']);
            $table->json('data');
            $table->timestamps();
        });

        Schema::table('squad_snapshots', function (Blueprint $table) {
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('week_id')->references('id')->on('weeks');
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
