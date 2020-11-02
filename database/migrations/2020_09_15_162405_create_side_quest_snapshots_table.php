<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSideQuestSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('side_quest_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->integer('week_id')->unsigned();
            $table->integer('side_quest_id')->unsigned();
            $table->string('name')->nullable();
            $table->integer('difficulty');
            $table->integer('experience_reward');
            $table->integer('favor_reward');
            $table->float('experience_per_moment');
            $table->timestamps();
        });

        Schema::table('side_quest_snapshots', function (Blueprint $table) {
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('side_quest_id')->references('id')->on('side_quests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('side_quest_snapshots');
    }
}
