<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('hero_id')->unsigned();
            $table->bigInteger('item_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('slots', function (Blueprint $table) {
            $table->foreign('slot_type_id')->references('id')->on('slot_types');
            $table->foreign('hero_id')->references('id')->on('heroes');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slots');
    }
}
