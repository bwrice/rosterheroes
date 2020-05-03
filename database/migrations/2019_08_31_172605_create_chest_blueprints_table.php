<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChestBlueprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chest_blueprints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id')->unique();
            $table->string('description')->nullable();
            $table->smallInteger('quality')->unsigned();
            $table->smallInteger('size')->unsigned();
            $table->bigInteger('min_gold')->unsigned();
            $table->bigInteger('max_gold')->unsigned();
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
        Schema::dropIfExists('chest_blueprints');
    }
}
