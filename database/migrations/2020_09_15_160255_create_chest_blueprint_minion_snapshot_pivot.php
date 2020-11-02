<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChestBlueprintMinionSnapshotPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chest_blueprint_minion_snapshot', function (Blueprint $table) {
            $table->integer('chest_blueprint_id')->unsigned();
            $table->bigInteger('minion_snapshot_id')->unsigned();
            $table->float('chance');
            $table->integer('count')->unsigned();
            $table->primary(['chest_blueprint_id', 'minion_snapshot_id'], 'c_blueprint_m_snapshot_primary');
            $table->timestamps();
        });

        Schema::table('chest_blueprint_minion_snapshot', function (Blueprint $table) {
            $table->foreign('chest_blueprint_id')->references('id')->on('chest_blueprints');
            $table->foreign('minion_snapshot_id')->references('id')->on('minion_snapshots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chest_blueprint_minion_snapshot');
    }
}
