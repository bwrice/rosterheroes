<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinionSkirmishPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minion_skirmish', function (Blueprint $table) {
            $table->integer('minion_id')->unsigned();
            $table->integer('skirmish_id')->unsigned();
            $table->integer('count');
            $table->primary(['minion_id', 'skirmish_id']);
            $table->timestamps();
        });

        Schema::table('attack_minion', function (Blueprint $table) {
            $table->foreign('minion_id')->references('id')->on('minions');
            $table->foreign('skirmish_id')->references('id')->on('skirmishes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('minion_skirmish');
    }
}
