<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasurableBoostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurable_boosts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('measurable_type_id')->unsigned();
            $table->morphs('booster');
            $table->integer('boost_level')->indexed();
            $table->timestamps();
        });

        Schema::table('measurable_boosts', function (Blueprint $table) {
            $table->foreign('measurable_type_id')->references('id')->on('measurable_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurable_boosts');
    }
}
