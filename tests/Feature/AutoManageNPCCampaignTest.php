<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\AutoManageNPCCampaign;
use App\Facades\CurrentWeek;
use App\Facades\NPC;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use App\Jobs\JoinQuestForNPCJob;
use App\Jobs\JoinSideQuestForNPCJob;
use App\Jobs\MoveNPCToProvinceJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AutoManageNPCCampaignTest extends NPCActionTest
{
    /**
     * @return AutoManageNPCCampaign
     */
    protected function getDomainAction()
    {
        return app(AutoManageNPCCampaign::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_for_managing_an_npc_campaign_if_the_current_week_is_locked()
    {
        NPC::shouldReceive('isNPC')->andReturn(true);
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(true);

        $squad = SquadFactory::new()->create();
        try {
            $this->getDomainAction()->execute($squad);
        } catch (\Exception $exception) {
            $this->assertEquals(AutoManageNPCCampaign::EXCEPTION_CODE_WEEK_LOCKED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_for_managing_an_npc_campaign_if_the_current_week_locks_too_soon()
    {
        NPC::shouldReceive('isNPC')->andReturn(true);
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);
        CurrentWeek::shouldReceive('adventuringLocksAt')->andReturn(now()->addMinutes(15));

        $squad = SquadFactory::new()->create();
        try {
            $this->getDomainAction()->execute($squad);
        } catch (\Exception $exception) {
            $this->assertEquals(AutoManageNPCCampaign::EXCEPTION_CODE_WEEK_LOCKS_TOO_SOON, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_dispatch_move_npc_jobs_chained_with_join_quest_and_side_quest_jobs()
    {
        NPC::shouldReceive('isNPC')->andReturn(true);
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);
        CurrentWeek::shouldReceive('adventuringLocksAt')->andReturn(now()->addDays(1));

        $questOne = QuestFactory::new()->create();
        $sideQuestFactoryOne = SideQuestFactory::new()->forQuestID($questOne->id);
        $questTwo = QuestFactory::new()->create();
        $sideQuestFactoryTwo = SideQuestFactory::new()->forQuestID($questTwo->id);

        NPC::shouldReceive('questsToJoin')->andReturn(collect([
            [
                'quest' => $questOne,
                'side_quests' => [
                    $sideQuestA = $sideQuestFactoryOne->create(),
                    $sideQuestB = $sideQuestFactoryOne->create(),
                    $sideQuestC = $sideQuestFactoryOne->create()
                ]
            ],
            [
                'quest' => $questTwo,
                'side_quests' => [
                    $sideQuestD = $sideQuestFactoryTwo->create(),
                    $sideQuestE = $sideQuestFactoryTwo->create(),
                    $sideQuestF = $sideQuestFactoryTwo->create()
                ]
            ]
        ]));

        Queue::fake();

        $squad = SquadFactory::new()->create();
        $this->getDomainAction()->execute($squad);

        Queue::assertPushed(MoveNPCToProvinceJob::class, function (MoveNPCToProvinceJob $job) use ($squad, $questOne) {
            return $job->npc->id === $squad->id && $job->province->id === $questOne->province_id;
        });

        Queue::assertPushedWithChain(MoveNPCToProvinceJob::class, [
            new JoinQuestForNPCJob($squad, $questOne),
            new JoinSideQuestForNPCJob($squad, $sideQuestA),
            new JoinSideQuestForNPCJob($squad, $sideQuestB),
            new JoinSideQuestForNPCJob($squad, $sideQuestC),
        ]);

        Queue::assertPushed(MoveNPCToProvinceJob::class, function (MoveNPCToProvinceJob $job) use ($squad, $questTwo) {
            return $job->npc->id === $squad->id && $job->province->id === $questTwo->province_id;
        });

        Queue::assertPushedWithChain(MoveNPCToProvinceJob::class, [
            new JoinQuestForNPCJob($squad, $questTwo),
            new JoinSideQuestForNPCJob($squad, $sideQuestD),
            new JoinSideQuestForNPCJob($squad, $sideQuestE),
            new JoinSideQuestForNPCJob($squad, $sideQuestF),
        ]);
    }

}
