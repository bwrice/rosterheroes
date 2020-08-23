<?php

namespace Tests\Feature;

use App\Domain\Actions\JoinSideQuestAction;
use App\Domain\Actions\NPC\JoinSideQuestForNPC;
use App\Domain\Actions\NPC\NPCAction;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Week;
use App\Facades\NPC;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use App\Helpers\EloquentMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JoinSideQuestForNPCTest extends NPCActionTest
{

    /**
     * @return NPCAction
     */
    protected function getDomainAction()
    {
        return app(JoinSideQuestForNPC::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_npc_squad_has_no_campaign_stop_for_the_quest_of_the_side_quest()
    {
        /** @var Week $week */
        $week = factory(Week::class)->states('as-current')->create();
        $squad = SquadFactory::new()->create();
        $campaignFactory = CampaignFactory::new()->withWeekID($week->id)->withSquadID($squad->id);
        $diffCampaignStop = CampaignStopFactory::new()->withCampaign($campaignFactory)->create();

        $sideQuest = SideQuestFactory::new()->create();

        NPC::shouldReceive('isNPC')->andReturn(true);

        try {
            $this->getDomainAction()->execute($squad, $sideQuest);
        } catch (\Exception $exception) {
            $this->assertEquals(JoinSideQuestForNPC::EXCEPTION_NO_VALID_CAMPAIGN_STOP, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_execute_join_side_quest_action_with_the_npc()
    {
        /** @var Week $week */
        $week = factory(Week::class)->states('as-current')->create();
        $squad = SquadFactory::new()->create();
        $campaignFactory = CampaignFactory::new()->withWeekID($week->id)->withSquadID($squad->id);
        $campaignStop = CampaignStopFactory::new()->withCampaign($campaignFactory)->create();

        $sideQuest = SideQuestFactory::new()->forQuestID($campaignStop->quest_id)->create();

        NPC::shouldReceive('isNPC')->andReturn(true);

        $mock = \Mockery::mock(JoinSideQuestAction::class)
            ->shouldReceive('execute')
            ->with(EloquentMatcher::withExpected($campaignStop), EloquentMatcher::withExpected($sideQuest))->getMock();
        $this->instance(JoinSideQuestAction::class, $mock);

        $this->getDomainAction()->execute($squad, $sideQuest);
    }
}
