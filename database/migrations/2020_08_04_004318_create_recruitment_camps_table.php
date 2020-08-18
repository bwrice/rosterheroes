<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentCampsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_camps', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('province_id')->unsigned();
            $table->string('name')->unique();
            $table->string('slug');
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_camps');
    }
}
