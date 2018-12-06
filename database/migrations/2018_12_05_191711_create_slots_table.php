<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slot_type_id')->unsigned();
            $table->morphs('has_slots');
            $table->nullableMorphs('slottable');
            $table->timestamps();
        });

        Schema::table('slots', function (Blueprint $table) {
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
        Schema::dropIfExists('slots');
    }
}
