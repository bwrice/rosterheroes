<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSquadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squads', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('slug');
            $table->string('name')->unique();
            $table->integer('user_id')->unsigned();
            $table->integer('squad_rank_id')->unsigned();
            $table->tinyInteger('mobile_storage_rank_id')->unsigned();
            $table->integer('province_id')->unsigned();
            $table->integer('nation_id')->unsigned()->nullable();
            $table->integer('spirit_essence')->unsigned()->default(0);
            $table->bigInteger('experience')->unsigned()->default(0);
            $table->bigInteger('gold')->unsigned()->default(0);
            $table->bigInteger('favor')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::table('squads', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('squad_rank_id')->references('id')->on('squad_ranks');
            $table->foreign('mobile_storage_rank_id')->references('id')->on('mobile_storage_ranks');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('nation_id')->references('id')->on('nations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squads');
    }
}
