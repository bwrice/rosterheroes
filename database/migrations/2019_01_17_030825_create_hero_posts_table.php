<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('squad_id')->unsigned();
            $table->integer('hero_race_id')->unsigned();
            $table->integer('hero_id')->unsigned()->nullable();
            $table->timestamps();
        });


        Schema::table('hero_posts', function (Blueprint $table) {
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('hero_race_id')->references('id')->on('hero_races');
            $table->foreign('hero_id')->references('id')->on('heroes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hero_posts');
    }
}
