<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnchantmentItemPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enchantment_item', function (Blueprint $table) {
            $table->integer('enchantment_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->primary(['enchantment_id', 'item_id']);
            $table->timestamps();
        });

        Schema::table('enchantment_item', function (Blueprint $table) {
            $table->foreign('enchantment_id')->references('id')->on('enchantments');
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
        Schema::dropIfExists('enchantment_item');
    }
}
