<?php

namespace Tests\Feature;

use App\Domain\Actions\ProcessSideQuestRewards;
use App\Domain\Actions\ProcessSideQuestVictoryRewards;
use App\Domain\Actions\RewardSquadForMinionKill;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use App\Factories\Models\MinionFactory;
use App\Factories\Models\SideQuestEventFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class ProcessSideQuestRewardsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_execute_victory_rewards_if_it_has_a_victory_event()
    {
        $eventFactory = SideQuestEventFactory::new()->withEventType(SideQuestEvent::TYPE_SIDE_QUEST_VICTORY);
        $sideQuestResult = SideQuestResultFactory::new()->withEvents(collect([
            $eventFactory
        ]))->create();

        $processVictoryMock = \Mockery::spy(ProcessSideQuestVictoryRewards::class);

        app()->instance(ProcessSideQuestVictoryRewards::class, $processVictoryMock);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $processVictoryMock->shouldHaveReceived('execute');
    }

    /**
     * @test
     */
    public function it_will_not_execute_victory_rewards_if_no_victory_event()
    {
        $eventFactory = SideQuestEventFactory::new()->withEventType(SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT);
        $sideQuestResult = SideQuestResultFactory::new()->withEvents(collect([
            $eventFactory
        ]))->create();

        $processVictoryMock = \Mockery::spy(ProcessSideQuestVictoryRewards::class);

        app()->instance(ProcessSideQuestVictoryRewards::class, $processVictoryMock);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $processVictoryMock->shouldNotHaveReceived('execute');
    }

    /**
     * @test
     */
    public function it_will_execute_reward_minion_kill_for_every_minion_killed()
    {
        $combatHero = CombatHeroFactory::new()->create();
        $heroCombatAttack = HeroCombatAttackFactory::new()->create();
        $combatMinion = CombatMinionFactory::new()->create();
        $eventFactory = SideQuestEventFactory::new()->heroKillsMinion($combatHero, $heroCombatAttack, $combatMinion);
        $sideQuestResult = SideQuestResultFactory::new()->withEvents(collect([
            $eventFactory,
            $eventFactory,
            $eventFactory
        ]))->create();

        $rewardMinionKillAction = \Mockery::spy(RewardSquadForMinionKill::class)
            ->shouldReceive('execute')
            ->times(3)
            ->getMock();

        app()->instance(RewardSquadForMinionKill::class, $rewardMinionKillAction);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);
    }

    /**
     * @test
     */
    public function it_will_reward_the_squad_experience_for_the_moments_lasted_in_battle()
    {
        $minionFactory = MinionFactory::new();
        $sideQuestFactory = SideQuestFactory::new()->withMinions(collect([
            $minionFactory,
            $minionFactory
        ]));

        $finalMoment = rand(6, 50);
        $sideQuestEventOne = SideQuestEventFactory::new()->withMoment(5);
        $sideQuestEventTwo = SideQuestEventFactory::new()->withMoment($finalMoment);

        $sideQuestResult = SideQuestResultFactory::new()->withSideQuest($sideQuestFactory)->withEvents(collect([
            $sideQuestEventTwo,
            $sideQuestEventOne
        ]))->create();

        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $previousExperience = $squad->experience;
        $experienceEarned = (int) ceil($sideQuestResult->sideQuest->getExperiencePerMoment() * $finalMoment);
        $this->assertGreaterThan(0, $experienceEarned);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $this->assertEquals($previousExperience + $experienceEarned, $squad->fresh()->experience);
    }

    /**
     * @test
     */
    public function it_will_save_the_rewards_processed_at_date_time()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();

        $this->assertNull($sideQuestResult->rewards_processed_at);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $this->assertNotNull($sideQuestResult->fresh()->rewards_processed_at);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_processed_at_already_set_on_side_quest_result()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create([
            'rewards_processed_at' => $now = Date::now()
        ]);

        try {
            /** @var ProcessSideQuestRewards $domainAction */
            $domainAction = app(ProcessSideQuestRewards::class);
            $domainAction->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals($now->timestamp, $sideQuestResult->fresh()->rewards_processed_at->timestamp);
            return;
        }
        $this->fail('Exception not thrown');
    }
}
