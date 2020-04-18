<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestRewards;
use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestsAction;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;

class ProcessWeeklySideQuestRewardsTest extends ProcessWeeklySideQuestsTest
{

    /**
     * @return ProcessWeeklySideQuestRewards
     */
    protected function getDomainAction(): ProcessWeeklySideQuestsAction
    {
        return app(ProcessWeeklySideQuestRewards::class);
    }

    protected function getBaseSideQuestResultFactory(): SideQuestResultFactory
    {
        return SideQuestResultFactory::new()->combatProcessed();
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
