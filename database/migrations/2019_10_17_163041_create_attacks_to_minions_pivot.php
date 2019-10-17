<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttacksToMinionsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_minion', function (Blueprint $table) {
            $table->integer('attack_id')->unsigned();
            $table->bigInteger('minion_id')->unsigned();
            $table->primary(['attack_id', 'minion_id']);
            $table->timestamps();
        });

        Schema::table('attack_minion', function (Blueprint $table) {
            $table->foreign('attack_id')->references('id')->on('attacks');
            $table->foreign('minion_id')->references('id')->on('minions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attacks_to_minions_pivot');
    }
}
