<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttackTitanPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attack_titan', function (Blueprint $table) {
            $table->integer('attack_id')->unsigned();
            $table->integer('titan_id')->unsigned();
            $table->primary(['attack_id', 'titan_id']);
            $table->timestamps();
        });

        Schema::table('attack_titan', function (Blueprint $table) {
            $table->foreign('attack_id')->references('id')->on('attacks');
            $table->foreign('titan_id')->references('id')->on('titans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attack_titan');
    }
}
