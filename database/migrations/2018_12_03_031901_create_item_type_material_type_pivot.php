<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTypeMaterialTypePivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_type_material_type', function (Blueprint $table) {
            $table->integer('i_type_id')->unsigned();
            $table->integer('m_type_id')->unsigned();
            $table->primary(['i_type_id', 'm_type_id']);
            $table->timestamps();
        });

        Schema::table('item_type_material_type', function (Blueprint $table) {
            $table->foreign('i_type_id')->references('id')->on('item_types');
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
        Schema::dropIfExists('item_type_material_type');
    }
}
