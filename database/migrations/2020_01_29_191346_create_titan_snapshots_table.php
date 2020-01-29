<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTitanSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titan_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('titan_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->unique(['titan_id', 'week_id']);
            $table->json('data');
            $table->timestamps();
        });

        Schema::table('titan_snapshots', function (Blueprint $table) {
            $table->foreign('titan_id')->references('id')->on('titans');
            $table->foreign('week_id')->references('id')->on('weeks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('titan_snapshots');
    }
}
