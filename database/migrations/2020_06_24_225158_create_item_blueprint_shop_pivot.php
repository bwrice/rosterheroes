<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemBlueprintShopPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_blueprint_shop', function (Blueprint $table) {
            $table->integer('item_blueprint_id')->unsigned();
            $table->bigInteger('shop_id')->unsigned();
            $table->primary(['item_blueprint_id', 'shop_id']);

            $table->foreign('item_blueprint_id')->references('id')->on('item_blueprints');
            $table->foreign('shop_id')->references('id')->on('shops');
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
