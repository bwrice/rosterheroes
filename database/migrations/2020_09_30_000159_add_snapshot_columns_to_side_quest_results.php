<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSnapshotColumnsToSideQuestResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('side_quest_results', function (Blueprint $table) {
            $table->bigInteger('squad_snapshot_id')->nullable();
            $table->bigInteger('side_quest_snapshot_id')->nullable();
        });

        Schema::table('side_quest_results', function (Blueprint $table) {
            $table->foreign('squad_snapshot_id')->references('id')->on('squad_snapshots');
            $table->foreign('side_quest_snapshot_id')->references('id')->on('side_quest_snapshots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
