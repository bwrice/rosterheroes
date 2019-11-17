<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinionSkirmishBlueprintPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minion_skirmish_blueprint', function (Blueprint $table) {
            $table->integer('minion_id')->unsigned();
            $table->integer('blueprint_id')->unsigned();
            $table->integer('count');
            $table->primary(['minion_id', 'blueprint_id']);
            $table->timestamps();
        });

        Schema::table('minion_skirmish_blueprint', function (Blueprint $table) {
            $table->foreign('minion_id')->references('id')->on('minions');
            $table->foreign('blueprint_id')->references('id')->on('skirmish_blueprints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minion_skirmish');
    }
}
