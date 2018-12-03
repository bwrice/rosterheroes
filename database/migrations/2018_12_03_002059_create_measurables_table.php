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
            $table->integer('measurable_type_id')->unsigned();
            $table->morphs('has_measurables');
            $table->integer('amount_raised')->unsigned();
            $table->timestamps();
        });

        Schema::table('measurables', function (Blueprint $table) {
            $table->foreign('measurable_type_id')->references('id')->on('measurable_types');
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
