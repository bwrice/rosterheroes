<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWagonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wagons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wagon_size_id')->unsigned();
            $table->integer('squad_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('wagons', function (Blueprint $table) {
            $table->foreign('wagon_size_id')->references('id')->on('wagon_sizes');
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
        Schema::dropIfExists('wagons');
    }
}
