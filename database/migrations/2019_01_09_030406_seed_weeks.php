<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedWeeks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $weeks = [
            [
                'name' => "The Week of Jerry Rice"
            ],
            [
                'name' => "The Week of Jim Brown"
            ],
            [
                'name' => "The Week of Micky Mantle"
            ],
            [
                'name' => "The Week of Joe Montana"
            ],
            [
                'name' => "The Week of Willie Mays"
            ],
            [
                'name' => "The Week of Babe Ruth"
            ],
            [
                'name' => "The Week of Hank Aaron"
            ],
            [
                'name' => "The Week of Johnny Unitas"
            ],
            [
                'name' => "The Week of Walter Payton"
            ]
        ];

        $start = \Carbon\Carbon::parse('2019-09-04 00:00:00')->setTimezone('UTC');

        foreach($weeks as $week) {

            $wednesday = $start->copy()->setTimezone('America/New_York');
            $offset = $wednesday->getOffset();
            $wednesday = $wednesday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

            $friday = $start->copy()->addDays(2)->setTimezone('America/New_York');
            $offset = $friday->getOffset();
            $friday = $friday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

            $sunday = $start->copy()->addDays(4)->setTimezone('America/New_York');
            $offset = $sunday->getOffset();
            $sunday = $sunday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

            $monday = $start->copy()->addDays(5)->setTimezone('America/New_York');
            $offset = $monday->getOffset();
            $monday = $monday->addHours(9)->subSeconds($offset)->setTimezone('UTC');

            \App\Weeks\Week::create([
                'name' => $week['name'],
                'proposals_scheduled_to_lock_at' => $wednesday,
                'diplomacy_scheduled_to_lock_at' => $friday,
                'everything_locks_at' => $sunday,
                'ends_at' => $monday
            ]);

            $start = $start->addWeek(1);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Weeks\Week::query()->truncate();
    }
}
