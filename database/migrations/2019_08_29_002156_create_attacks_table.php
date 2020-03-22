<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attacks', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name')->unique();
            $table->integer('attacker_position_id')->unsigned();
            $table->integer('target_position_id')->unsigned();
            $table->integer('damage_type_id')->unsigned();
            $table->integer('target_priority_id')->unsigned();
            $table->string('config_path');
            $table->timestamps();
        });

        Schema::table('attacks', function (Blueprint $table) {
            $table->foreign('attacker_position_id')->references('id')->on('combat_positions');
            $table->foreign('target_position_id')->references('id')->on('combat_positions');
            $table->foreign('damage_type_id')->references('id')->on('damage_types');
            $table->foreign('target_priority_id')->references('id')->on('target_priorities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attacks');
    }
}
