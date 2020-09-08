<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurableSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurable_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->bigInteger('measurable_id')->unsigned();
            $table->bigInteger('hero_snapshot_id')->unsigned();
            $table->unique(['measurable_id', 'hero_snapshot_id']);
            $table->integer('pre_buffed_amount')->unsigned();
            $table->integer('buffed_amount')->unsigned();
            $table->integer('current_amount')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurable_snapshots');
    }
}
