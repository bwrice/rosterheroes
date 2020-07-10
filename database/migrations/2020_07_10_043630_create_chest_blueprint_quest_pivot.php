<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChestBlueprintQuestPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chest_blueprint_quest', function (Blueprint $table) {
            $table->integer('chest_blueprint_id')->unsigned();
            $table->integer('quest_id')->unsigned();
            $table->float('chance');
            $table->integer('count')->unsigned();
            $table->primary(['chest_blueprint_id', 'quest_id']);
            $table->timestamps();
        });

        Schema::table('chest_blueprint_quest', function (Blueprint $table) {
            $table->foreign('chest_blueprint_id')->references('id')->on('chest_blueprints');
            $table->foreign('quest_id')->references('id')->on('quests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chest_blueprint_quest');
    }
}
