<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestsAction;
use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestSideEffects;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessWeeklySideQuestSideEffectsTest extends ProcessWeeklySideQuestsTest
{

    protected function getBaseSideQuestResultFactory(): SideQuestResultFactory
    {
        return SideQuestResultFactory::new()->combatProcessed()->rewardsProcessed();
    }

    protected function getDomainAction(): ProcessWeeklySideQuestsAction
    {
        return app(ProcessWeeklySideQuestSideEffects::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_are_side_quest_results_for_current_week_without_combat_processed()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);
        $sideQuestResult = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory)->create();
        $this->assertNull($sideQuestResult->combat_processed_at);

        try {
            $this->getDomainAction()->execute(rand(1,6));
        } catch (\Exception $exception) {
            return;
        }
        $this->fail("Exception not thrown");
    }
}
