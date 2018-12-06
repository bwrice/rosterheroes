<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBaseSlotTypePivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_base_slot_type', function (Blueprint $table) {
            $table->integer('item_base_id')->unsigned();
            $table->integer('slot_type_id')->unsigned();
            $table->primary(['item_base_id', 'slot_type_id']);
            $table->timestamps();
        });

        Schema::table('item_base_slot_type', function (Blueprint $table) {
            $table->foreign('item_base_id')->references('id')->on('item_bases');
            $table->foreign('slot_type_id')->references('id')->on('slot_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_base_slot_type');
    }
}
