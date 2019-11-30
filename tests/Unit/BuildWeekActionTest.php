<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildWeekAction;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildWeekActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
    * @test
    */
    public function adventuring_will_lock_the_upcoming_sunday_if_starting_point_is_before_wednesday()
    {
        $startingPoint = Date::now()->next(CarbonInterface::TUESDAY);
        $upcomingSunday = $startingPoint->next(CarbonInterface::SUNDAY);

        /** @var BuildWeekAction $domainAction */
        $domainAction = app(BuildWeekAction::class);
        $week = $domainAction->execute($startingPoint);

        $this->assertEquals($upcomingSunday->dayOfYear, $week->adventuring_locks_at->dayOfYear);
    }

    /**
    * @test
    */
    public function adventuring_will_lock_the_following_sunday_if_starting_point_is_after_wednesday()
    {
        $startingPoint = Date::now()->next(CarbonInterface::WEDNESDAY);
        $followingSunday = $startingPoint->next(CarbonInterface::SUNDAY)->addWeek();

        /** @var BuildWeekAction $domainAction */
        $domainAction = app(BuildWeekAction::class);
        $week = $domainAction->execute($startingPoint);

        $this->assertEquals($followingSunday->dayOfYear, $week->adventuring_locks_at->dayOfYear);
    }
}
