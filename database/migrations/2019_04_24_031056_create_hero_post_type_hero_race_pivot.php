<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroPostTypeHeroRacePivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_post_type_hero_race', function (Blueprint $table) {
            $table->integer('hero_post_type_id')->unsigned();
            $table->integer('hero_race_id')->unsigned();
            $table->primary(['hero_post_type_id', 'hero_race_id']);
            $table->timestamps();
        });

        Schema::table('hero_post_type_hero_race', function (Blueprint $table) {
            $table->foreign('hero_post_type_id')->references('id')->on('hero_post_types');
            $table->foreign('hero_race_id')->references('id')->on('hero_races');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hero_post_type_hero_race');
    }
}
