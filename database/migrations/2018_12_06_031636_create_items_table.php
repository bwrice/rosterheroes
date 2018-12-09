<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_class_id')->unsigned();
            $table->integer('item_type_id')->unsigned();
            $table->integer('material_type_id')->unsigned();
            $table->integer('item_blueprint_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('material_type_id')->references('id')->on('material_types');
            $table->foreign('item_class_id')->references('id')->on('item_classes');
            $table->foreign('item_blueprint_id')->references('id')->on('item_blueprints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
