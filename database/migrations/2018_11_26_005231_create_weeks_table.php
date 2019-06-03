<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weeks', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('weekly_game_players_queued_at')->nullable();
            $table->dateTime('proposals_scheduled_to_lock_at');
            $table->dateTime('proposals_processed_at')->nullable();
            $table->dateTime('diplomacy_scheduled_to_lock_at');
            $table->dateTime('diplomacy_processed_at')->nullable();
            $table->dateTime('everything_locks_at');
            $table->dateTime('ends_at');
            $table->dateTime('finalized_at')->nullable();
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
        Schema::dropIfExists('weeks');
    }
}
