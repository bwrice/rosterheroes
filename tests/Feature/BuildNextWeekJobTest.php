<?php

namespace Tests\Feature;

use App\Domain\Models\Week;
use App\Jobs\BuildNextWeekJob;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuildNextWeekJobTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_create_the_next_week_if_latest_week_exists()
    {
        $latestWeek = factory(Week::class)->create();
        Week::setTestLatest($latestWeek);
        $nextWeek = BuildNextWeekJob::dispatchNow();
        $this->assertEquals($latestWeek->ends_at->addWeek()->timestamp, $nextWeek->ends_at->timestamp);
    }

    /**
     * @test
     */
    public function it_will_create_a_new_week_if_no_week_exists()
    {
        Week::useNullTestLatest();
        /** @var Week $week */
        $week = BuildNextWeekJob::dispatchNow();
        $this->assertGreaterThan(Date::now()->timestamp, $week->ends_at->timestamp);
        $this->assertLessThan(Date::now()->addWeeks(2)->timestamp, $week->ends_at->timestamp);
    }
}
