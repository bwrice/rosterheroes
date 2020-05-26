<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChestBlueprintsToSideQuestsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chest_blueprint_side_quest', function (Blueprint $table) {
            $table->integer('chest_blueprint_id')->unsigned();
            $table->integer('side_quest_id')->unsigned();
            $table->float('chance');
            $table->integer('count')->unsigned();
            $table->primary(['chest_blueprint_id', 'side_quest_id'], 'chest_bp_side_quest_composite');
            $table->timestamps();
        });

        Schema::table('chest_blueprint_side_quest', function (Blueprint $table) {
            $table->foreign('chest_blueprint_id')->references('id')->on('chest_blueprints');
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
        Schema::dropIfExists('chest_blueprint_side_quest');
    }
}
