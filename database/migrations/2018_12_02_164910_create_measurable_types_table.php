<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasurableTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurable_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('measurable_group_id')->unsigned();
            $table->timestamps();
        });


        Schema::table('measurable_types', function (Blueprint $table) {
            $table->foreign('measurable_group_id')->references('id')->on('measurable_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurable_types');
    }
}
