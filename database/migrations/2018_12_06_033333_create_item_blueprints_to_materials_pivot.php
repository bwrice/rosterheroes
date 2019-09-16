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
            $table->integer('blueprint_id')->unsigned();
            $table->integer('material_id')->unsigned();
            $table->primary(['blueprint_id', 'material_id']);
            $table->timestamps();
        });

        Schema::table('item_blueprint_material', function (Blueprint $table) {
            $table->foreign('blueprint_id')->references('id')->on('item_blueprints');
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
