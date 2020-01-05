<?php

namespace Tests\Unit;

use App\Domain\Actions\FinalizeWeekStepOneAction;
use App\Domain\Models\Week;
use App\Exceptions\StepOneException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class FinalizeWeekStepOneActionTest extends TestCase
{
    /** @var Week */
    protected $week;

    /** @var FinalizeWeekStepOneAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        $this->domainAction = app(FinalizeWeekStepOneAction::class);
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_not_long_enough_after_adventuring_ends()
    {
        Date::setTestNow($this->week->adventuring_locks_at->addHours(Week::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS - 1));

        try {
            $this->domainAction->execute($this->week);
        } catch (StepOneException $exception) {
            $this->assertEquals(StepOneException::INVALID_TIME_TO_FINALIZE, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

}
