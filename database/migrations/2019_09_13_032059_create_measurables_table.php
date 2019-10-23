<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasurablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurables', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('measurable_type_id')->unsigned();
            $table->integer('hero_id')->unsigned();
            $table->integer('amount_raised')->unsigned();
            $table->timestamps();
        });

        Schema::table('measurables', function (Blueprint $table) {
            $table->foreign('measurable_type_id')->references('id')->on('measurable_types');
            $table->foreign('hero_id')->references('id')->on('heroes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurables');
    }
}
