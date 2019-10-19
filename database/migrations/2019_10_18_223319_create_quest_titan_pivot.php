<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestTitanPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_titan', function (Blueprint $table) {
            $table->integer('quest_id')->unsigned();
            $table->integer('titan_id')->unsigned();
            $table->integer('count')->unsigned();
            $table->primary(['quest_id', 'titan_id']);
            $table->timestamps();
        });

        Schema::table('quest_titan', function (Blueprint $table) {
            $table->foreign('quest_id')->references('id')->on('quests');
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
        Schema::table('quest_titan', function (Blueprint $table) {
            //
        });
    }
}
