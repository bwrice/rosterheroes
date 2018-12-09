<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnchantmentItemBlueprintPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enchantment_item_blueprint', function (Blueprint $table) {
            $table->integer('ench_id')->unsigned();
            $table->integer('blueprint_id')->unsigned();
            $table->primary(['ench_id', 'blueprint_id']);
            $table->timestamps();
        });

        Schema::table('enchantment_item_blueprint', function (Blueprint $table) {
            $table->foreign('ench_id')->references('id')->on('enchantments');
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
        Schema::dropIfExists('enchantment_item_blueprint');
    }
}
