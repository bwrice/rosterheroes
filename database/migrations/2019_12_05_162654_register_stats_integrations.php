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
        // Testing
        \App\StatsIntegrationType::query()->create([
            'name' => \App\External\Stats\MockIntegration::INTEGRATION_NAME
        ]);
        // My Sports Feed
        \App\StatsIntegrationType::query()->create([
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
