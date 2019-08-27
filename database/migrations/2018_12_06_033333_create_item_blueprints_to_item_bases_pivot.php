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
            $table->integer('base_id')->unsigned();
            $table->integer('blueprint_id')->unsigned();
            $table->primary(['blueprint_id', 'base_id']);
            $table->timestamps();
        });

        Schema::table('item_base_item_blueprint', function (Blueprint $table) {
            $table->foreign('base_id')->references('id')->on('item_bases');
            $table->foreign('blueprint_id')->references('id')->on('item_blueprints');
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
