<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integration_type_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->string('external_id');
            $table->unique(['integration_type_id', 'team_id', 'external_id']);
            $table->timestamps();
        });

        Schema::table('external_teams', function (Blueprint $table) {
            $table->foreign('integration_type_id')->references('id')->on('stats_integration_types');
            $table->foreign('team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_teams');
    }
}
