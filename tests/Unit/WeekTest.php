<?php

namespace Tests\Unit;

use App\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeekTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function you_can_get_the_current_week()
    {
        $week = Week::current();

        $this->assertNotNull($week, "Week exists");
    }

    /**
     * @test
     */
    public function proposals_are_scheduled_to_lock_on_wednesday_at_nine()
    {
        Week::all()->each(function (Week $week) {
            $proposalsLockAt = $week->proposals_scheduled_to_lock_at->copy()->setTimezone('America/New_York');
            $this->assertEquals(9, $proposalsLockAt->hour);
            $this->assertEquals('Wednesday', $proposalsLockAt->englishDayOfWeek);
        });
    }

    /**
     * @test
     */
    public function diplomacy_is_scheduled_to_lock_on_friday_at_nine()
    {
        Week::all()->each(function (Week $week) {
            $diplomacyLocksAt = $week->diplomacy_scheduled_to_lock_at->copy()->setTimezone('America/New_York');
            $this->assertEquals(9, $diplomacyLocksAt->hour);
            $this->assertEquals('Friday', $diplomacyLocksAt->englishDayOfWeek);
        });
    }

    /**
     * @test
     */
    public function every_locks_on_sunday_at_nine()
    {
        Week::all()->each(function (Week $week) {
            $everythingLocksAt = $week->everything_locks_at->copy()->setTimezone('America/New_York');
            $this->assertEquals(9, $everythingLocksAt->hour);
            $this->assertEquals('Sunday', $everythingLocksAt->englishDayOfWeek);
        });
    }

    /**
     * @test
     */
    public function weeks_end_on_monday_at_nine()
    {
        Week::all()->each(function (Week $week) {
            $endsAt = $week->ends_at->copy()->setTimezone('America/New_York');
            $this->assertEquals(9, $endsAt->hour);
            $this->assertEquals('Monday', $endsAt->englishDayOfWeek);
        });
    }
//
//    /**
//     * @test
//     */
//    public function it_can_get_the_previous_week()
//    {
//
//    }
//
//    /**
//     * @test
//     */
//    public function diplomacy_wont_close_until_at_least_a_day_after_proposals_are_processed()
//    {
//
//    }

    // TODO:
    /*
     * 1) Test you can get current week
     * 2) Test you can't
     */
}
