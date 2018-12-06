<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_base_id')->unsigned();
            $table->string('name');
            $table->integer('grade');
            $table->timestamps();
        });

        Schema::table('item_types', function (Blueprint $table) {
            $table->foreign('item_base_id')->references('id')->on('item_bases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_types');
    }
}
