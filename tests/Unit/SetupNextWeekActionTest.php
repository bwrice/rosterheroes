<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildNewCurrentWeekAction;
use App\Domain\Actions\SetupNextWeekAction;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\BuildNextWeekException;
use App\Exceptions\BuildWeekException;
use App\Facades\CurrentWeek;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class SetupNextWeekActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_there_is_no_current_week()
    {
        CurrentWeek::partialMock()->shouldReceive('exists')->andReturn(false);

        /** @var SetupNextWeekAction $domainAction */
        $domainAction = app(SetupNextWeekAction::class);

        try {
            $domainAction->execute();
        } catch (BuildNextWeekException $exception) {
            $this->assertEquals(BuildNextWeekException::CODE_INVALID_CURRENT_WEEK, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_execute_build_new_current_week_action()
    {
        CurrentWeek::partialMock()->shouldReceive('exists')->andReturn(true);

        /** @var Week $week */
        $week = factory(Week::class)->create();
        $mock = \Mockery::mock(BuildNewCurrentWeekAction::class)
            ->shouldReceive('execute', 1)
            ->andReturn($week)
            ->getMock();
        app()->instance(BuildNewCurrentWeekAction::class, $mock);

        /** @var SetupNextWeekAction $domainAction */
        $domainAction = app(SetupNextWeekAction::class);
        $domainAction->execute();
    }

    /**
    * @test
    */
    public function it_will_set_made_current_at_date()
    {
        CurrentWeek::partialMock()->shouldReceive('exists')->andReturn(true);

        /** @var Week $week */
        $week = factory(Week::class)->create();
        $mock = \Mockery::mock(BuildNewCurrentWeekAction::class)
            ->shouldReceive('execute', 1)
            ->andReturn($week)
            ->getMock();
        app()->instance(BuildNewCurrentWeekAction::class, $mock);

        /** @var SetupNextWeekAction $domainAction */
        $domainAction = app(SetupNextWeekAction::class);
        $domainAction->execute();
        $this->assertNotNull($week->fresh()->made_current_at);
    }
}
