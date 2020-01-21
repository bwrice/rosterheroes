<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minions', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->string('slug');
            $table->string('config_path');
            $table->integer('enemy_type_id')->unsigned();
            $table->integer('combat_position_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('minions', function (Blueprint $table) {
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
        Schema::dropIfExists('minions');
    }
}
