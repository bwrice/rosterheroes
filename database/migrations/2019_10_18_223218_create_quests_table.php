<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->integer('level')->unsigned();
            $table->integer('province_id')->unsigned();
            $table->increments('travel_type_id')->unsigned();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('quests', function (Blueprint $table) {
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('travel_type_id')->references('id')->on('travel_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quests');
    }
}
