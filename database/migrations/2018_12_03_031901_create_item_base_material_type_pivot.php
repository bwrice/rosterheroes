<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBaseMaterialTypePivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_base_material_type', function (Blueprint $table) {
            $table->integer('item_base_id')->unsigned();
            $table->integer('material_type_id')->unsigned();
            $table->primary(['item_base_id', 'material_type_id'], 'item_base_material_type_primary_key');
            $table->timestamps();
        });

        Schema::table('item_base_material_type', function (Blueprint $table) {
            $table->foreign('item_base_id')->references('id')->on('item_bases');
            $table->foreign('material_type_id')->references('id')->on('material_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_base_material_type');
    }
}
