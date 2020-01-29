<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroSnapshotToAttackSnapshotPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_snapshot_hero_snapshot', function (Blueprint $table) {
            $table->bigInteger('attack_snapshot_id')->unsigned();
            $table->bigInteger('hero_snapshot_id')->unsigned();
            $table->json('data');
            $table->timestamps();
        });

        Schema::table('attack_snapshot_hero_snapshot', function (Blueprint $table) {
            $table->foreign('attack_snapshot_id')->references('id')->on('attack_snapshots');
            $table->foreign('hero_snapshot_id')->references('id')->on('hero_snapshots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attack_snapshot_hero_snapshot');
    }
}
