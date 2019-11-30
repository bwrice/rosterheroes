<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildFirstWeekAction;
use App\Domain\Models\Week;
use App\Exceptions\BuildWeekException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildFirstWeekActionTest extends TestCase
{
    /**
    * @test
    */
    public function building_first_week_will_throw_an_exception_if_there_is_a_current_week()
    {
        $week = factory(Week::class)->state('as-current')->create();

        try {
            /** @var BuildFirstWeekAction $domainAction */
            $domainAction = app(BuildFirstWeekAction::class);
            $domainAction->execute();
        } catch (BuildWeekException $exception) {
            $this->assertEquals(BuildWeekException::CODE_INVALID_CURRENT_WEEK, $exception->getCode());
            $currentWeek = Week::current();
            $this->assertEquals($currentWeek->id, $week->id);
            return;
        }
        $this->fail("Exception not thrown");
    }
}
