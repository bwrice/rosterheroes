<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChestBlueprintTitanPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chest_blueprint_titan', function (Blueprint $table) {
            $table->integer('chest_blueprint_id')->unsigned();
            $table->integer('titan_id')->unsigned();
            $table->float('chance');
            $table->integer('count')->unsigned();
            $table->primary(['chest_blueprint_id', 'titan_id']);
            $table->timestamps();
        });

        Schema::table('chest_blueprint_titan', function (Blueprint $table) {
            $table->foreign('chest_blueprint_id')->references('id')->on('chest_blueprints');
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
        Schema::dropIfExists('chest_blueprint_titan');
    }
}
