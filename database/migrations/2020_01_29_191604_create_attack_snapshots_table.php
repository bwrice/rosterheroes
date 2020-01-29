<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttackSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_snapshots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('attack_id')->unsigned();
            $table->integer('week_id')->unsigned();
            $table->unique(['attack_id', 'week_id']);
            $table->json('data');
            $table->timestamps();
        });

        Schema::table('attack_snapshots', function (Blueprint $table) {
            $table->foreign('attack_id')->references('id')->on('attacks');
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
        Schema::dropIfExists('attack_snapshots');
    }
}
