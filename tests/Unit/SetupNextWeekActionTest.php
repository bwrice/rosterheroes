<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildNewCurrentWeekAction;
use App\Domain\Actions\SetupNextWeekAction;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Exceptions\BuildNextWeekException;
use App\Exceptions\BuildWeekException;
use App\Facades\CurrentWeek;
use App\Jobs\MakeWeekCurrentJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SetupNextWeekActionTest extends TestCase
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
    public function it_will_dispatch_make_week_current()
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

        Queue::assertPushed(MakeWeekCurrentJob::class, function (MakeWeekCurrentJob $job) use ($week) {
            return $job->week->id === $week->id;
        });
    }
}
