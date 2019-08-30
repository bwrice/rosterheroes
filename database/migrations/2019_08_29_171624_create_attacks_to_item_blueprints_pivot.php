<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttacksToItemBlueprintsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_item_blueprint', function (Blueprint $table) {
            $table->integer('attack_id')->unsigned();
            $table->integer('item_blueprint_id')->unsigned();
            $table->primary(['attack_id', 'item_blueprint_id']);
            $table->timestamps();
        });

        Schema::table('attack_item_blueprint', function (Blueprint $table) {
            $table->foreign('attack_id')->references('id')->on('attacks');
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
        Schema::dropIfExists('attack_item_blueprint');
    }
}
