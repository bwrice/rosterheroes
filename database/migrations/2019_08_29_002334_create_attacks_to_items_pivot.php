<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttacksToItemsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_item', function (Blueprint $table) {
            $table->integer('attack_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->primary(['attack_id', 'item_id']);
            $table->timestamps();
        });

        Schema::table('attack_item', function (Blueprint $table) {
            $table->foreign('attack_id')->references('id')->on('attacks');
            $table->foreign('item_id')->references('id')->on('items');
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
