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
            $table->integer('priority_type_id')->unsigned();
            $table->json('resource_costs');
            $table->json('requirements');
            $table->timestamps();
        });

        Schema::table('attacks', function (Blueprint $table) {
            $table->foreign('damage_type_id')->references('id')->on('damage_types');
            $table->foreign('priority_type_id')->references('id')->on('attack_types');
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
