<?php

namespace Tests\Feature;

use App\Domain\Actions\Snapshots\BuildSnapshot;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

abstract class BuildWeeklySnapshotTest extends TestCase
{
    /**
     * @test
     */
    public function building_a_weekly_snapshot_will_throw_an_exception_if_the_week_is_not_finalizing()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->subHour());

        try {
            $this->getDomainAction()->execute();
        } catch (\Exception $exception) {
            $this->assertEquals(BuildSnapshot::EXCEPTION_CODE_WEEK_NOT_FINALIZING, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @return mixed
     */
    abstract protected function getDomainAction();
}
