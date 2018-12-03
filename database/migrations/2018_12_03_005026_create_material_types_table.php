<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('material_group_id')->unsigned();
            $table->string('name');
            $table->integer('grade');
            $table->timestamps();
        });

        Schema::table('material_types', function (Blueprint $table) {
            $table->foreign('material_group_id')->references('id')->on('material_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_types');
    }
}
