<?php

namespace Tests\Feature;

use App\Domain\ProcessSideQuestResultSideEffects;
use App\Factories\Models\SideQuestResultFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class ProcessSideQuestResultSideEffectsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ProcessSideQuestResultSideEffects
     */
    protected function getDomainAction()
    {
        return app(ProcessSideQuestResultSideEffects::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_side_effects_already_processed()
    {
        $processedAt = Date::now()->subDays(rand(1,10));
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->sideEffectsProcessed($processedAt)->create();
        try {
            $this->getDomainAction()->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals($processedAt->timestamp, $sideQuestResult->fresh()->side_effects_processed_at->timestamp);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_combat_isnt_processed_at_yet()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();
        $this->assertNull($sideQuestResult->combat_processed_at);
        try {
            $this->getDomainAction()->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertNull($sideQuestResult->side_effects_processed_at);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_update_side_effects_processed_at()
    {
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->rewardsProcessed()->create();
        $this->getDomainAction()->execute($sideQuestResult);
        $this->assertNotNull($sideQuestResult->fresh()->side_effects_processed_at);
    }
}
