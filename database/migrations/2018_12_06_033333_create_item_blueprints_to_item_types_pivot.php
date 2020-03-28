<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBlueprintsToItemTypesPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_blueprint_item_type', function (Blueprint $table) {
            $table->integer('item_blueprint_id')->unsigned();
            $table->integer('item_type_id')->unsigned();
            $table->primary(['item_blueprint_id', 'item_type_id'], 'item_blueprint_item_type_primary');
            $table->timestamps();
        });

        Schema::table('item_blueprint_item_type', function (Blueprint $table) {
            $table->foreign('item_blueprint_id')->references('id')->on('item_blueprints');
            $table->foreign('item_type_id')->references('id')->on('item_types');
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
