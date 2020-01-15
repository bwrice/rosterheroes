<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildNewCurrentWeekAction;
use App\Facades\CurrentWeek;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildNewCurrentWeekActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
    * @test
    */
    public function adventuring_will_lock_the_upcoming_sunday_if_starting_point_is_before_wednesday()
    {
        $testNow = Date::now()->next(CarbonInterface::TUESDAY);
        $upcomingSunday = $testNow->next(CarbonInterface::SUNDAY);
        Date::setTestNow($testNow);

        /** @var BuildNewCurrentWeekAction $domainAction */
        $domainAction = app(BuildNewCurrentWeekAction::class);
        $week = $domainAction->execute();

        $this->assertEquals($upcomingSunday->dayOfYear, $week->adventuring_locks_at->dayOfYear);
    }

    /**
    * @test
    */
    public function adventuring_will_lock_the_following_sunday_if_starting_point_is_after_wednesday()
    {
        $testNow = Date::now()->next(CarbonInterface::WEDNESDAY);
        $followingSunday = $testNow->next(CarbonInterface::SUNDAY)->addWeek();
        Date::setTestNow($testNow);

        /** @var BuildNewCurrentWeekAction $domainAction */
        $domainAction = app(BuildNewCurrentWeekAction::class);
        $week = $domainAction->execute();

        $this->assertEquals($followingSunday->dayOfYear, $week->adventuring_locks_at->dayOfYear);
    }

    /**
    * @test
    */
    public function adventuring_locks_at_will_be_sunday_at_noon_new_york_time()
    {
        /** @var BuildNewCurrentWeekAction $domainAction */
        $domainAction = app(BuildNewCurrentWeekAction::class);
        $week = $domainAction->execute();

        $adventuringLocksAt = $week->adventuring_locks_at;
        $this->assertEquals('12:00:00', $adventuringLocksAt->setTimezone('America/New_York')->format('H:i:s') );
    }

    /**
    * @test
    */
    public function the_week_returned_will_be_the_new_current_week()
    {
        /** @var BuildNewCurrentWeekAction $domainAction */
        $domainAction = app(BuildNewCurrentWeekAction::class);
        $week = $domainAction->execute();

        $this->assertEquals($week->id, CurrentWeek::id());
    }
}
