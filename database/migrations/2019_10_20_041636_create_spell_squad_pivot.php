<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpellSquadPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spell_squad', function (Blueprint $table) {
            $table->integer('spell_id')->unsigned();
            $table->integer('squad_id')->unsigned();
            $table->primary(['spell_id', 'squad_id']);
            $table->timestamps();
        });

        Schema::table('spell_squad', function (Blueprint $table) {
            $table->foreign('spell_id')->references('id')->on('spells');
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
        Schema::dropIfExists('spell_squad');
    }
}
