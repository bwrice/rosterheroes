<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('grade')->unsigned();
            $table->bigInteger('squad_id')->unsigned();
            $table->dateTime('opened_at');
            $table->bigInteger('gold')->unsigned();
            $table->integer('chest_blueprint_id')->unsigned()->nullable();
        });

        Schema::table('chests', function (Blueprint $table) {
            $table->foreign('squad_id')->references('id')->on('squads');
            $table->foreign('chest_blueprint_id')->references('id')->on('chest_blueprints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chests');
    }
}