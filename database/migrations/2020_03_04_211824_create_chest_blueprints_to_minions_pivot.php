<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChestBlueprintsToMinionsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chest_blueprint_minion', function (Blueprint $table) {
            $table->integer('chest_blueprint_id')->unsigned();
            $table->integer('minion_id')->unsigned();
            $table->primary(['chest_blueprint_id', 'minion_id']);
            $table->timestamps();
        });

        Schema::table('chest_blueprint_minion', function (Blueprint $table) {
            $table->foreign('chest_blueprint_id')->references('id')->on('chest_blueprints');
            $table->foreign('minion_id')->references('id')->on('minions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chest_blueprint_minion');
    }
}
