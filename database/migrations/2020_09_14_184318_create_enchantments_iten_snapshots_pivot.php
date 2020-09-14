<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnchantmentsItenSnapshotsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enchantment_item_snapshot', function (Blueprint $table) {
            $table->integer('enchantment_id')->unsigned();
            $table->bigInteger('item_snapshot_id')->unsigned();
            $table->primary(['enchantment_id', 'item_snapshot_id', 'enchantment_item_snapshot_primary']);
            $table->timestamps();
        });

        Schema::table('enchantment_item_snapshot', function (Blueprint $table) {
            $table->foreign('enchantment_id')->references('id')->on('enchantments');
            $table->foreign('item_snapshot_id')->references('id')->on('item_snapshots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enchantment_item_snapshot');
    }
}
