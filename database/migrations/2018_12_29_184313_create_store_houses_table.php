<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_houses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_house_type_id')->unsigned();
            $table->integer('squad_id')->unsigned();
            $table->timestamps();
        });


        Schema::table('store_houses', function (Blueprint $table) {
            $table->foreign('store_house_type_id')->references('id')->on('store_house_types');
            $table->foreign('squad_id')->references('id')->on('squads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_houses');
    }
}
