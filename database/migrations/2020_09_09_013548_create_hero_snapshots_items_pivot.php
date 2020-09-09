<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroSnapshotsItemsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_snapshot_item', function (Blueprint $table) {
            $table->bigInteger('hero_snapshot_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->primary(['hero_snapshot_id', 'item_id']);
            $table->timestamps();
        });

        Schema::table('hero_snapshot_item', function (Blueprint $table) {
            $table->foreign('hero_snapshot_id')->references('id')->on('hero_snapshots');
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
        Schema::dropIfExists('hero_snapshot_item');
    }
}
