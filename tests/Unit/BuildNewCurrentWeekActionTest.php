<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildNewCurrentWeekAction;
use App\Facades\CurrentWeek;
use App\Facades\WeekService;
use App\Jobs\FinalizeWeekJob;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildNewCurrentWeekActionTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

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
    public function adventuring_will_lock_the_following_sunday_if_starting_point_is_after_friday()
    {
        $testNow = Date::now()->next(CarbonInterface::SATURDAY);
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
    public function it_will_dispatch_finalize_week_step_on_correctly_delayed()
    {
        $extraHoursDelay = rand(2, 8);
        Config::set('week.finalize_extra_hours_delay', $extraHoursDelay);

        /** @var BuildNewCurrentWeekAction $domainAction */
        $domainAction = app(BuildNewCurrentWeekAction::class);
        $weekCreated = $domainAction->execute();
        Queue::assertPushed(FinalizeWeekJob::class, 1);
        Queue::assertPushed(FinalizeWeekJob::class, function (FinalizeWeekJob $job) use ($weekCreated, $extraHoursDelay) {
            /** @var CarbonInterface $delay */
            $delay = $job->delay;
            $expectedDelay = WeekService::finalizingStartsAt($weekCreated->adventuring_locks_at)->clone()->addHours($extraHoursDelay);
            return ($delay->timestamp === $expectedDelay->timestamp) && ($job->step === 1);
        });
    }
}
