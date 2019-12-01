<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildWeeklyPlayerSpiritsAction;
use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildWeeklyPlayerSpiritsActionTest extends TestCase
{
    /** @var Week */
    protected $week;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->state('as-current')->create();
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_there_are_no_valid_games()
    {
        try {
            /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
            $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
            $domainAction->execute($this->week);
        } catch (\Exception $exception) {
            // so we don't get a warning
            $this->assertTrue(true);
            return;
        }

        $this->fail("Exception not throw");
    }
}
