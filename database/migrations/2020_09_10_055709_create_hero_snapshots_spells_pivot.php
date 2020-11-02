<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroSnapshotsSpellsPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_snapshot_spell', function (Blueprint $table) {
            $table->bigInteger('hero_snapshot_id')->unsigned();
            $table->integer('spell_id')->unsigned();
            $table->primary(['hero_snapshot_id', 'spell_id']);
            $table->timestamps();
        });

        Schema::table('hero_snapshot_spell', function (Blueprint $table) {
            $table->foreign('hero_snapshot_id')->references('id')->on('hero_snapshots');
            $table->foreign('spell_id')->references('id')->on('spells');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hero_snapshot_spell');
    }
}
