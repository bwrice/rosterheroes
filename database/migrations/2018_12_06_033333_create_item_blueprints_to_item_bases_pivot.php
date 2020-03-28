<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBlueprintsToItemBasesPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_base_item_blueprint', function (Blueprint $table) {
            $table->integer('item_base_id')->unsigned();
            $table->integer('item_blueprint_id')->unsigned();
            $table->primary(['item_base_id', 'item_blueprint_id'], 'item_base_item_blueprint_primary');
            $table->timestamps();
        });

        Schema::table('item_base_item_blueprint', function (Blueprint $table) {
            $table->foreign('item_base_id')->references('id')->on('item_bases');
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
        //
    }
}
