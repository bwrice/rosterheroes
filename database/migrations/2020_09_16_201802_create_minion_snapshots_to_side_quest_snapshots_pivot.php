<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinionSnapshotsToSideQuestSnapshotsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_snapshot_sq_snapshot', function (Blueprint $table) {
            $table->bigInteger('minion_snapshot_id')->unsigned();
            $table->bigInteger('side_quest_snapshot_id')->unsigned();
            $table->primary(['minion_snapshot_id', 'side_quest_snapshot_id'], 'm_snapshot_sq_snapshot_primary');
            $table->integer('count');
            $table->timestamps();
        });

        Schema::table('m_snapshot_sq_snapshot', function (Blueprint $table) {
            $table->foreign('minion_snapshot_id')->references('id')->on('minion_snapshots');
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
        Schema::dropIfExists('minion_snapshot_side_quest_snapshot');
    }
}
