<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkirmishSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skirmish_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('skirmish_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->unique(['skirmish_id', 'week_id']);
            $table->timestamps();
        });

        Schema::table('skirmish_snapshots', function (Blueprint $table) {
            $table->foreign('skirmish_id')->references('id')->on('skirmishes');
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
        Schema::dropIfExists('skirmish_snapshots');
    }
}
