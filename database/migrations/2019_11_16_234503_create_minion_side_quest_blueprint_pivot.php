<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinionSideQuestBlueprintPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minion_side_quest_blueprint', function (Blueprint $table) {
            $table->integer('minion_id')->unsigned();
            $table->integer('side_quest_blueprint_id')->unsigned();
            $table->integer('count');
            $table->primary(['minion_id', 'side_quest_blueprint_id'], 'minion_blueprint_primary');
            $table->timestamps();
        });

        Schema::table('minion_side_quest_blueprint', function (Blueprint $table) {
            $table->foreign('minion_id')->references('id')->on('minions');
            $table->foreign('side_quest_blueprint_id')->references('id')->on('side_quest_blueprints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minion_side_quest_blueprint');
    }
}
