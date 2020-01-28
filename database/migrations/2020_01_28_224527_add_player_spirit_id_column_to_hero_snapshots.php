<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlayerSpiritIdColumnToHeroSnapshots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hero_snapshots', function (Blueprint $table) {
            $table->bigInteger('player_spirit_id')->unsigned();
            $table->foreign('player_spirit_id')->references('id')->on('player_spirits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hero_snapshots', function (Blueprint $table) {
            $table->dropColumn('player_spirit_id');
        });
    }
}
