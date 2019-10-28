<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_house_type_id')->unsigned();
            $table->integer('squad_id')->unsigned();
            $table->integer('province_id')->unsigned();
            $table->timestamps();
        });


        Schema::table('residences', function (Blueprint $table) {
            $table->foreign('residence_type_id')->references('id')->on('residence_types');
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('residences');
    }
}
