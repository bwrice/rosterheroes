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
            $table->integer('squad_rank_id')->unsigned();
            $table->tinyInteger('mobile_storage_rank_id')->unsigned();
            $table->integer('province_id')->unsigned();
            $table->integer('nation_id')->unsigned()->nullable();
            $table->integer('spirit_essence')->unsigned()->default(0);
            $table->bigInteger('experience')->unsigned()->default(0);
            $table->bigInteger('gold')->unsigned()->default(0);
            $table->bigInteger('favor')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::table('squad_snapshots', function (Blueprint $table) {
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('squad_rank_id')->references('id')->on('squad_ranks');
            $table->foreign('mobile_storage_rank_id')->references('id')->on('mobile_storage_ranks');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('nation_id')->references('id')->on('nations');
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
