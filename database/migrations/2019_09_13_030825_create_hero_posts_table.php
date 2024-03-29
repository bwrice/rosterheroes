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
            $table->bigIncrements('id');
            $table->integer('hero_post_type_id')->unsigned();
            $table->bigInteger('squad_id')->unsigned();
            $table->timestamps();
        });


        Schema::table('hero_posts', function (Blueprint $table) {
            $table->foreign('hero_post_type_id')->references('id')->on('hero_post_types');
            $table->foreign('squad_id')->references('id')->on('squads');
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
