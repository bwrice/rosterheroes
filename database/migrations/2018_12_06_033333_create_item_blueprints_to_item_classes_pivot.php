<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBlueprintsToItemClassesPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_blueprint_item_class', function (Blueprint $table) {
            $table->integer('blueprint_id')->unsigned();
            $table->integer('i_class_id')->unsigned();
            $table->primary(['blueprint_id', 'i_class_id']);
            $table->timestamps();
        });

        Schema::table('item_blueprint_item_class', function (Blueprint $table) {
            $table->foreign('blueprint_id')->references('id')->on('item_blueprints');
            $table->foreign('i_class_id')->references('id')->on('item_classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_blueprints_to_item_classes_pivot');
    }
}
