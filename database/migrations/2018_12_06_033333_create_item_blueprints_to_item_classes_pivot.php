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
            $table->integer('item_blueprint_id')->unsigned();
            $table->integer('item_class_id')->unsigned();
            $table->primary(['item_blueprint_id', 'item_class_id'], 'item_blueprint_item_class_primary');
            $table->timestamps();
        });

        Schema::table('item_blueprint_item_class', function (Blueprint $table) {
            $table->foreign('item_blueprint_id')->references('id')->on('item_blueprints');
            $table->foreign('item_class_id')->references('id')->on('item_classes');
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
