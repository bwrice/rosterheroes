<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedEmailSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $subscriptions = [
            \App\Domain\Models\EmailSubscription::NEWSLETTER,
            \App\Domain\Models\EmailSubscription::SQUAD_NOTIFICATIONS
        ];

        foreach ($subscriptions as $emailSub) {
            \App\Domain\Models\EmailSubscription::query()->create([
                'name' => $emailSub
            ]);
        }
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
