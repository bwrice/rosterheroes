<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('continent_id')->unsigned();
            $table->integer('territory_id')->unsigned();
            $table->string('name')->unique();
            $table->integer('nation_id')->unsigned()->nullable();
            $table->string('color');
            $table->float('realm_x', 10, 4);
            $table->float('realm_y', 10, 4);
            $table->float('focus_scale', 8 , 4);
            $table->integer('focus_x');
            $table->integer('focus_y');
            $table->timestamps();
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->foreign('continent_id')->references('id')->on('continents');
            $table->foreign('territory_id')->references('id')->on('territories');
            $table->foreign('nation_id')->references('id')->on('nations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provinces');
    }
}
