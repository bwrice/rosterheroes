<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurableTypeStatTypePivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurable_type_stat_type', function (Blueprint $table) {
            $table->integer('measurable_type_id')->unsigned();
            $table->integer('stat_type_id')->unsigned();
            $table->primary(['measurable_type_id', 'stat_type_id'], 'measurable_type_stat_type_primary');
            $table->timestamps();
        });

        Schema::table('measurable_type_stat_type', function (Blueprint $table) {
            $table->foreign('measurable_type_id')->references('id')->on('measurable_types');
            $table->foreign('stat_type_id')->references('id')->on('stat_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurable_type_stat_type');
    }
}
