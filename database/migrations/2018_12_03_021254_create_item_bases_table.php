<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_bases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_group_id')->unsigned();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('item_bases', function (Blueprint $table) {
            $table->foreign('item_group_id')->references('id')->on('item_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_bases');
    }
}
