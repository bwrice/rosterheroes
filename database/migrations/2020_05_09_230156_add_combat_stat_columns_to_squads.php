<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCombatStatColumnsToSquads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('squads', function (Blueprint $table) {
            $table->bigInteger('damage_dealt')->default(0);
            $table->bigInteger('minion_damage_dealt')->default(0);
            $table->bigInteger('titan_damage_dealt')->default(0);
            $table->bigInteger('side_quest_damage_dealt')->default(0);
            $table->bigInteger('quest_damage_dealt')->default(0);
            $table->bigInteger('damage_taken')->default(0);
            $table->bigInteger('side_quest_damage_taken')->default(0);
            $table->bigInteger('minion_damage_taken')->default(0);
            $table->bigInteger('titan_damage_taken')->default(0);
            $table->bigInteger('minion_kills')->default(0);
            $table->bigInteger('side_quest_kills')->default(0);
            $table->bigInteger('quest_kills')->default(0);
            $table->bigInteger('titan_kills')->default(0);
            $table->bigInteger('combat_kills')->default(0);
            $table->bigInteger('side_quest_deaths')->default(0);
            $table->bigInteger('minion_deaths')->default(0);
            $table->bigInteger('titan_deaths')->default(0);
            $table->bigInteger('combat_deaths')->default(0);
            $table->bigInteger('attacks_blocked')->default(0);
            $table->bigInteger('side_quest_victories')->default(0);
            $table->bigInteger('side_quest_defeats')->default(0);
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
