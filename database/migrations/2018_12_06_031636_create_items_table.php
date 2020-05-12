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
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->integer('item_class_id')->unsigned();
            $table->integer('item_type_id')->unsigned();
            $table->integer('material_id')->unsigned();
            $table->integer('item_blueprint_id')->unsigned()->nullable();
            $table->nullableMorphs('has_items');
            $table->string('name')->nullable();
            $table->bigInteger('damage_dealt')->unsigned()->default(0);
            $table->bigInteger('minion_damage_dealt')->unsigned()->default(0);
            $table->bigInteger('titan_damage_dealt')->unsigned()->default(0);
            $table->bigInteger('side_quest_damage_dealt')->unsigned()->default(0);
            $table->bigInteger('quest_damage_dealt')->unsigned()->default(0);
            $table->bigInteger('minion_kills')->unsigned()->default(0);
            $table->bigInteger('titan_kills')->unsigned()->default(0);
            $table->bigInteger('combat_kills')->unsigned()->default(0);
            $table->bigInteger('side_quest_kills')->unsigned()->default(0);
            $table->bigInteger('quest_kills')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('material_id')->references('id')->on('materials');
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
