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
            $table->string('name');
            $table->integer('damage_type_id')->unsigned();
            $table->integer('target_range_id')->unsigned();
            $table->integer('target_priority_id')->unsigned();
            $table->integer('grade')->unsigned();
            $table->float('speed_rating');
            $table->float('base_damage_rating');
            $table->float('damage_modifier_rating');
            $table->json('resource_costs');
            $table->json('requirements');
            $table->timestamps();
        });

        Schema::table('attacks', function (Blueprint $table) {
            $table->foreign('damage_type_id')->references('id')->on('damage_types');
            $table->foreign('target_range_id')->references('id')->on('target_ranges');
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
