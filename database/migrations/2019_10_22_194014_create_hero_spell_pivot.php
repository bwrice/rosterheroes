<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeroSpellPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_spell', function (Blueprint $table) {
            $table->integer('hero_id')->unsigned();
            $table->integer('spell_id')->unsigned();
            $table->primary(['hero_id', 'spell_id']);
            $table->timestamps();
        });

        Schema::table('hero_spell', function (Blueprint $table) {
            $table->foreign('hero_id')->references('id')->on('heroes');
            $table->foreign('spell_id')->references('id')->on('spells');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hero_spell');
    }
}
