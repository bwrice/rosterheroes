<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sport_id')->unsigned();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('stat_types', function (Blueprint $table) {
            $table->foreign('sport_id')->references('id')->on('sports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats');
    }
}
