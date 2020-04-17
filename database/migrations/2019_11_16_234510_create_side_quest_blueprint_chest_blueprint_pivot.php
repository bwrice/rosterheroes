<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSideQuestBlueprintChestBlueprintPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chest_blueprint_side_quest_blueprint', function (Blueprint $table) {
            $table->integer('chest_blueprint_id')->unsigned();
            $table->integer('side_quest_blueprint_id')->unsigned();
            $table->integer('count');
            $table->primary(['chest_blueprint_id', 'side_quest_blueprint_id'], 'chest_bp_side_quest_bp_primary');
            $table->timestamps();
        });

        Schema::table('chest_blueprint_side_quest_blueprint', function (Blueprint $table) {
            $table->foreign('chest_blueprint_id')->references('id')->on('chest_blueprints');
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
        Schema::dropIfExists('chest_blueprint_side_quest_blueprint');
    }
}
