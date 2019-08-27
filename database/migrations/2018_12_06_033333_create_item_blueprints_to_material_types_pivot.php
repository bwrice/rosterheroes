<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBlueprintsToMaterialTypesPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_blueprint_material_type', function (Blueprint $table) {
            $table->integer('blueprint_id')->unsigned();
            $table->integer('m_type_id')->unsigned();
            $table->primary(['blueprint_id', 'm_type_id']);
            $table->timestamps();
        });

        Schema::table('item_blueprint_material_type', function (Blueprint $table) {
            $table->foreign('blueprint_id')->references('id')->on('item_blueprints');
            $table->foreign('m_type_id')->references('id')->on('material_types');
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
