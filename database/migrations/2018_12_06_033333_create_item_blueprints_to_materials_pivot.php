<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBlueprintsToMaterialsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_blueprint_material', function (Blueprint $table) {
            $table->integer('item_blueprint_id')->unsigned();
            $table->integer('material_id')->unsigned();
            $table->primary(['item_blueprint_id', 'material_id'], 'item_blueprint_material_primary');
            $table->timestamps();
        });

        Schema::table('item_blueprint_material', function (Blueprint $table) {
            $table->foreign('item_blueprint_id')->references('id')->on('item_blueprints');
            $table->foreign('material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
