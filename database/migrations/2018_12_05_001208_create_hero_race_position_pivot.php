<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroRacePositionPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_race_position', function (Blueprint $table) {
            $table->integer('hero_race_id')->unsigned();
            $table->integer('position_id')->unsigned();
            $table->primary(['hero_race_id', 'position_id']);
            $table->timestamps();
        });

        Schema::table('hero_race_position', function (Blueprint $table) {
            $table->foreign('hero_race_id')->references('id')->on('hero_races');
            $table->foreign('position_id')->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hero_race_position');
    }
}
