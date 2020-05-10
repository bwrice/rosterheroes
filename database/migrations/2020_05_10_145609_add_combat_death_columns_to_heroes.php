<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCombatDeathColumnsToHeroes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->bigInteger('side_quest_deaths')->default(0);
            $table->bigInteger('minion_deaths')->default(0);
            $table->bigInteger('titan_deaths')->default(0);
            $table->bigInteger('combat_deaths')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
