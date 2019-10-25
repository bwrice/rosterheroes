<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titans', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->string('slug');
            $table->unsignedInteger('base_level');
            $table->smallInteger('base_damage_rating');
            $table->smallInteger('damage_multiplier_rating');
            $table->smallInteger('health_rating');
            $table->smallInteger('protection_rating');
            $table->smallInteger('combat_speed_rating');
            $table->smallInteger('block_rating');
            $table->integer('enemy_type_id')->unsigned();
            $table->integer('combat_position_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('titans', function (Blueprint $table) {
            $table->foreign('enemy_type_id')->references('id')->on('enemy_types');
            $table->foreign('combat_position_id')->references('id')->on('combat_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('titans');
    }
}
