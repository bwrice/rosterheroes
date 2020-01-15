<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildInitialWeekAction;
use App\Domain\Actions\BuildNewCurrentWeekAction;
use App\Domain\Models\Week;
use App\Exceptions\BuildWeekException;
use App\Facades\CurrentWeek;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Mockery\Mock;
use Tests\TestCase;

class BuildInitialWeekActionTest extends TestCase
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
    public function building_first_week_will_throw_an_exception_if_there_is_a_current_week()
    {
        CurrentWeek::partialMock()->shouldReceive('exists')->andReturn(true);

        try {
            /** @var BuildInitialWeekAction $domainAction */
            $domainAction = app(BuildInitialWeekAction::class);
            $domainAction->execute();
        } catch (BuildWeekException $exception) {
            $this->assertEquals(BuildWeekException::CODE_INVALID_CURRENT_WEEK, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_execute_build_new_current_week_action()
    {
        CurrentWeek::partialMock()->shouldReceive('exists')->andReturn(false);

        $mock = \Mockery::mock(BuildNewCurrentWeekAction::class)->shouldReceive('execute', 1)->getMock();
        app()->instance(BuildNewCurrentWeekAction::class, $mock);
        /** @var BuildInitialWeekAction $domainAction */
        $domainAction = app(BuildInitialWeekAction::class);
        $domainAction->execute();
    }
}
