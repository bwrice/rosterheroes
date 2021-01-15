<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\AutoManageNPC;
use App\Domain\Actions\NPC\FindChestsToOpen;
use App\Domain\Actions\NPC\FindQuestsToJoin;
use App\Domain\Actions\NPC\FindSpiritsToEmbodyHeroes;
use App\Facades\NPC;
use App\Factories\Models\ChestFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\QuestFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use App\Jobs\EmbodyNPCHeroJob;
use App\Jobs\JoinQuestForNPCJob;
use App\Jobs\JoinSideQuestForNPCJob;
use App\Jobs\MoveNPCToProvinceJob;
use App\Jobs\OpenChestJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AutoManageNPCTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return AutoManageNPC
     */
    protected function getDomainAction()
    {
        return app(AutoManageNPC::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_open_chests_jobs_for_an_npc()
    {
        $chests = collect();

        $chestsCount = rand(2, 5);
        for ($i = 1; $i <= $chestsCount; $i++) {
            $chests->push(ChestFactory::new()->create());
        }

        NPC::shouldReceive('isNPC')->andReturn(true);

        $npc = SquadFactory::new()->create();

        $mock = $this->getMockBuilder(FindChestsToOpen::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())->method('execute')->willReturn($chests);

        $this->instance(FindChestsToOpen::class, $mock);

        Queue::fake();
        $this->getDomainAction()->execute($npc, 100, 120, [
            AutoManageNPC::ACTION_OPEN_CHESTS
        ]);


        // Test job arguments
        Queue::assertPushed(OpenChestJob::class, function (OpenChestJob $job) use ($chests) {
            return in_array($job->chest->id, $chests->pluck('id')->toArray());
        });

        // Test jobs chained the right amount
        $chain = [];
        for ($i = 1; $i <= ($chestsCount - 1); $i++) {
            $chain[] = OpenChestJob::class;
        }
        Queue::assertPushedWithChain(OpenChestJob::class, $chain);
    }

    /**
     * @test
     */
    public function it_will_dispatch_fast_travel_chain_with_join_quests_and_side_quests_jobs()
    {
        NPC::shouldReceive('isNPC')->andReturn(true);

        $npc = SquadFactory::new()->create();

        $questA = QuestFactory::new()->create();
        $sideQuestsA = collect();
        $sideQuestAFactory = SideQuestFactory::new()->forQuestID($questA->id);
        for ($i = 1; $i <= $npc->getSideQuestsPerQuest(); $i++) {
            $sideQuestsA->push($sideQuestAFactory->create());
        }

        $questB = QuestFactory::new()->create();
        $sideQuestsB = collect();
        $sideQuestBFactory = SideQuestFactory::new()->forQuestID($questB->id);
        for ($i = 1; $i <= $npc->getSideQuestsPerQuest(); $i++) {
            $sideQuestsB->push($sideQuestBFactory->create());
        }

        $mock = $this->getMockBuilder(FindQuestsToJoin::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())->method('execute')->willReturn(collect([
            [
                'quest' => $questA,
                'side_quests' => $sideQuestsA
            ],
            [
                'quest' => $questB,
                'side_quests' => $sideQuestsB
            ]
        ]));

        $this->instance(FindQuestsToJoin::class, $mock);

        Queue::fake();
        $this->getDomainAction()->execute($npc, 100, 120, [
            AutoManageNPC::ACTION_JOIN_QUESTS
        ]);

        $chain[] = JoinQuestForNPCJob::class;
        foreach ($sideQuestsA as $sideQuest) {
            $chain[] = JoinSideQuestForNPCJob::class;
        }
        $chain[] = MoveNPCToProvinceJob::class;
        $chain[] = JoinQuestForNPCJob::class;
        foreach ($sideQuestsB as $sideQuest) {
            $chain[] = JoinSideQuestForNPCJob::class;
        }

        Queue::assertPushedWithChain(MoveNPCToProvinceJob::class, $chain);
    }

    /**
     * @test
     */
    public function it_will_dispatch_embody_hero_jobs_for_npc()
    {
        NPC::shouldReceive('isNPC')->andReturn(true);

        $npc = SquadFactory::new()->create();

        $heroA = HeroFactory::new()->create();
        $playerSpiritA = PlayerSpiritFactory::new()->create();

        $heroB = HeroFactory::new()->create();
        $playerSpiritB = PlayerSpiritFactory::new()->create();

        $returnValue = collect([
            [
                'hero' => $heroA,
                'player_spirit' => $playerSpiritA
            ],
            [
                'hero' => $heroB,
                'player_spirit' => $playerSpiritB
            ]
        ]);

        $mock = \Mockery::mock(FindSpiritsToEmbodyHeroes::class)
            ->shouldReceive('execute')
            ->andReturn($returnValue)
            ->getMock();

        $this->app->instance(FindSpiritsToEmbodyHeroes::class, $mock);

        Queue::fake();
        $this->getDomainAction()->execute($npc, 100, 120, [
            AutoManageNPC::ACTION_EMBODY_HEROES
        ]);

        Queue::assertPushedWithChain(EmbodyNPCHeroJob::class, [
            EmbodyNPCHeroJob::class
        ], function (EmbodyNPCHeroJob $job) use ($heroA, $playerSpiritA) {
            return $job->hero->id === $heroA->id
                && $job->playerSpirit->id === $playerSpiritA->id;
        });
    }
}
