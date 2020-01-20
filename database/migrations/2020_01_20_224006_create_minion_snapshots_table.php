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
            $table->integer('minion_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->json('data');
            $table->timestamps();
        });

        Schema::table('minion_snapshots', function (Blueprint $table) {
            $table->foreign('minion_id')->references('id')->on('minions');
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
        Schema::dropIfExists('minion_snapshots');
    }
}
