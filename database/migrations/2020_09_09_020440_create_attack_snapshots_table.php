<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttackSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->integer('attack_id')->unsigned();
            $table->bigInteger('hero_snapshot_id')->unsigned();
            $table->integer('damage')->unsigned();
            $table->float('combat_speed');
            $table->string('name')->unique();
            $table->integer('attacker_position_id')->unsigned();
            $table->integer('target_position_id')->unsigned();
            $table->integer('damage_type_id')->unsigned();
            $table->integer('target_priority_id')->unsigned();
            $table->smallInteger('tier')->unsigned();
            $table->smallInteger('targets_count')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('attack_snapshots', function (Blueprint $table) {
            $table->foreign('attack_id')->references('id')->on('attacks');
            $table->foreign('hero_snapshot_id')->references('id')->on('hero_snapshots');
            $table->foreign('attacker_position_id')->references('id')->on('combat_positions');
            $table->foreign('target_position_id')->references('id')->on('combat_positions');
            $table->foreign('damage_type_id')->references('id')->on('damage_types');
            $table->foreign('target_priority_id')->references('id')->on('target_priorities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attack_snapshots');
    }
}
