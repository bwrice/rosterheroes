<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->bigInteger('item_id')->unsigned();
            $table->bigInteger('hero_snapshot_id')->unsigned();
            $table->integer('item_type_id')->unsigned();
            $table->integer('material_id')->unsigned();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::table('item_snapshots', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('hero_snapshot_id')->references('id')->on('hero_snapshots');
            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_snapshots');
    }
}
