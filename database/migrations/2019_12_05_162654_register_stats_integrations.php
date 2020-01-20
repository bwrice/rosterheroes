<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RegisterStatsIntegrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // My Sports Feed
        \App\Domain\Models\StatsIntegrationType::query()->create([
            'name' => \App\External\Stats\MySportsFeed\MySportsFeed::INTEGRATION_NAME
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
