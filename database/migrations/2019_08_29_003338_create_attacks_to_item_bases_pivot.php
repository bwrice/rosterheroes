<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttacksToItemBasesPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_item_base', function (Blueprint $table) {
            $table->integer('attack_id')->unsigned();
            $table->integer('item_base_id')->unsigned();
            $table->primary(['attack_id', 'item_base_id']);
            $table->timestamps();
        });

        Schema::table('attack_item_base', function (Blueprint $table) {
            $table->foreign('attack_id')->references('id')->on('attacks');
            $table->foreign('item_base_id')->references('id')->on('item_bases');
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
