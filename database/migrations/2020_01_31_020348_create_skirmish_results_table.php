<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkirmishResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skirmish_results', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->uuid('uuid');
            $table->bigInteger('squad_id')->unsigned();
            $table->integer('skirmish_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('skirmish_results', function (Blueprint $table) {
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('skirmish_id')->references('id')->on('skirmishes');
            $table->foreign('week_id')->references('id')->on('weeks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skirmish_results');
    }
}
