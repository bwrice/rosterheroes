<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBlueprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_blueprints', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_type_id')->unsigned()->nullable();
            $table->integer( 'material_type_id' )->unsigned()->nullable();
            $table->integer('item_base_id')->unsigned()->nullable();
            $table->integer('item_group_id')->unsigned()->nullable();
            $table->integer('item_class_id')->unsigned()->nullable();
            $table->integer('enchantments_power')->nullable();
            $table->integer('attacks_power')->nullable();
            $table->string('item_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('item_blueprints', function (Blueprint $table) {
            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('material_type_id')->references('id')->on('material_types');
            $table->foreign('item_base_id')->references('id')->on('item_bases');
            $table->foreign('item_group_id')->references('id')->on('item_groups');
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
        Schema::dropIfExists('item_blueprints');
    }
}
