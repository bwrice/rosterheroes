<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTitanSnapshotToAttackSnapshotPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_snapshot_titan_snapshot', function (Blueprint $table) {
            $table->bigInteger('attack_snapshot_id')->unsigned();
            $table->bigInteger('titan_snapshot_id')->unsigned();
            $table->primary(['attack_snapshot_id', 'titan_snapshot_id'], 'primary_composite');
            $table->timestamps();
        });

        Schema::table('attack_snapshot_titan_snapshot', function (Blueprint $table) {
            $table->foreign('attack_snapshot_id')->references('id')->on('attack_snapshots');
            $table->foreign('titan_snapshot_id')->references('id')->on('titan_snapshots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attack_snapshot_titan_snapshot');
    }
}
