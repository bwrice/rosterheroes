<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChestBlueprintItemBlueprintPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chest_blueprint_item_blueprint', function (Blueprint $table) {
            $table->integer('chest_blueprint_id')->unsigned();
            $table->integer('item_blueprint_id')->unsigned();
            $table->float('chance')->unsigned();
            $table->primary(['chest_blueprint_id', 'item_blueprint_id'], 'chest_bp_item_bp');
            $table->timestamps();
        });


        Schema::table('chest_blueprint_item_blueprint', function (Blueprint $table) {
            $table->foreign('chest_blueprint_id')->references('id')->on('chest_blueprints');
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
        Schema::dropIfExists('chest_blueprint_item_blueprint');
    }
}
