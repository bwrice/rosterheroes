<?php

namespace Tests\Feature;

use App\Domain\Actions\ConvertMinionSnapshotIntoCombatant;
use App\Domain\Actions\ProcessSideQuestRewards;
use App\Domain\Actions\ProcessSideQuestVictoryRewards;
use App\Domain\Actions\RewardSquadForMinionKill;
use App\Factories\Combat\CombatantFactory;
use App\Factories\Combat\CombatAttackFactory;
use App\Factories\Models\MinionFactory;
use App\Factories\Models\MinionSnapshotFactory;
use App\Factories\Models\SideQuestEventFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\SideQuestResult;
use App\Factories\Models\SideQuestSnapshotFactory;
use App\Factories\Models\SquadSnapshotFactory;
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
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->withEvents(collect([
            $eventFactory
        ]))->create();

        $processVictoryMock = \Mockery::spy(ProcessSideQuestVictoryRewards::class)
            ->shouldReceive('execute')
            ->andReturn([
                'experience' => rand(1, 1000),
                'favor' => rand(1, 1000)
            ])->getMock();

        app()->instance(ProcessSideQuestVictoryRewards::class, $processVictoryMock);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $sideQuestResult = $sideQuestResult->fresh();
    }

    /**
     * @test
     */
    public function it_will_not_execute_victory_rewards_if_no_victory_event()
    {
        $eventFactory = SideQuestEventFactory::new()->withEventType(SideQuestEvent::TYPE_SIDE_QUEST_DEFEAT);
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->withEvents(collect([
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
        $combatHero = CombatantFactory::new()->create();
        $heroCombatAttack = CombatAttackFactory::new()->create();
        $minionSnapshot = MinionSnapshotFactory::new()->create();
        $combatMinion = CombatantFactory::new()->withSourceUuid($minionSnapshot->uuid)->create();
        $eventFactory = SideQuestEventFactory::new()->heroKillsMinion($combatHero, $heroCombatAttack, $combatMinion);
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->withEvents(collect([
            $eventFactory->withMoment(rand(1, 10)),
            $eventFactory->withMoment(rand(1, 10)),
            $eventFactory->withMoment(rand(1, 10))
        ]))->create();

        $rewardMinionKillAction = \Mockery::spy(RewardSquadForMinionKill::class)
            ->shouldReceive('execute')
            ->times(3)
            ->andReturn([
                'experience' => $experienceReward = rand(100, 1000),
                'favor' => $favorReward = rand(100, 1000)
            ])
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
        $finalMoment = rand(6, 50);
        $sideQuestEventOne = SideQuestEventFactory::new()->withMoment(5);
        $sideQuestEventTwo = SideQuestEventFactory::new()->withMoment($finalMoment);

        $squadSnapshot = SquadSnapshotFactory::new()->create();
        $sideQuestSnapshot = SideQuestSnapshotFactory::new()->create();

        $sideQuestResult = SideQuestResultFactory::new()
            ->forSquadSnapshot($squadSnapshot->id)
            ->forSideQuestSnapshot($sideQuestSnapshot->id)
            ->combatProcessed()
            ->withEvents(collect([
                $sideQuestEventTwo,
                $sideQuestEventOne
            ]))->create();

        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $previousExperience = $squad->experience;
        $experienceEarned = (int) ceil($sideQuestSnapshot->experience_per_moment * $finalMoment);
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
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->create();

        $this->assertNull($sideQuestResult->rewards_processed_at);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $this->assertNotNull($sideQuestResult->fresh()->rewards_processed_at);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_rewards_processed_at_already_set_on_side_quest_result()
    {
        $sideQuestResult = SideQuestResultFactory::new()->combatProcessed()->create([
            'rewards_processed_at' => $now = Date::now()
        ]);

        try {
            /** @var ProcessSideQuestRewards $domainAction */
            $domainAction = app(ProcessSideQuestRewards::class);
            $domainAction->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals(ProcessSideQuestRewards::EXCEPTION_CODE_REWARDS_ALREADY_PROCESSED, $exception->getCode());
            $this->assertEquals($now->timestamp, $sideQuestResult->fresh()->rewards_processed_at->timestamp);
            return;
        }
        $this->fail('Exception not thrown');
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_combat_not_processed_on_side_quest_result()
    {
        $sideQuestResult = SideQuestResultFactory::new()->create();

        try {
            /** @var ProcessSideQuestRewards $domainAction */
            $domainAction = app(ProcessSideQuestRewards::class);
            $domainAction->execute($sideQuestResult);
        } catch (\Exception $exception) {
            $this->assertEquals(ProcessSideQuestRewards::EXCEPTION_CODE_COMBAT_NOT_PROCESSED, $exception->getCode());
            return;
        }
        $this->fail('Exception not thrown');
    }

    /**
     * @test
     */
    public function it_will_save_the_experience_rewarded_to_the_side_quest_result()
    {
        $finalMoment = rand(6, 50);
        $minionSnapshot = MinionSnapshotFactory::new()->create();
        /** @var ConvertMinionSnapshotIntoCombatant $convertToCombatant */
        $convertToCombatant = app(ConvertMinionSnapshotIntoCombatant::class);
        $minionCombatant1 = $convertToCombatant->execute($minionSnapshot);
        $minionSnapshot = MinionSnapshotFactory::new()->create();
        $minionCombatant2 = $convertToCombatant->execute($minionSnapshot);
        $heroKillsMinionEvent1 = SideQuestEventFactory::new()
            ->heroKillsMinion(null, null, $minionCombatant1)
            ->withMoment(5);
        $heroKillsMinionEvent2 = SideQuestEventFactory::new()
            ->heroKillsMinion(null, null, $minionCombatant2)
            ->withMoment(5);
        $victoryEvent = SideQuestEventFactory::new()->sideQuestVictory()->withMoment($finalMoment);

        $sideQuestSnapshot = SideQuestSnapshotFactory::new()->create();
        $sideQuestResult = SideQuestResultFactory::new()
            ->forSideQuestSnapshot($sideQuestSnapshot->id)
            ->combatProcessed()
            ->withEvents(collect([
                $heroKillsMinionEvent1,
                $heroKillsMinionEvent2,
                $victoryEvent
        ]))->create();

        $experienceForMoments = (int) ceil($sideQuestSnapshot->experience_per_moment * $finalMoment);
        $this->assertGreaterThan(0, $experienceForMoments);

        $this->assertNull($sideQuestResult->experience_rewarded);

        $rewardMinionKillMock = \Mockery::mock(RewardSquadForMinionKill::class)
            ->shouldReceive('execute')
            ->times(2)
            ->andReturn([
                'experience' => $minionKillExperience = rand(10, 999),
                'favor' => 0
            ])->getMock();

        $this->app->instance(RewardSquadForMinionKill::class, $rewardMinionKillMock);

        $victoryMock = \Mockery::mock(ProcessSideQuestVictoryRewards::class)
            ->shouldReceive('execute')
            ->andReturn([
                'experience' => $victoryExperience = rand(100, 9999),
                'favor' => 0
            ])->getMock();

        $this->app->instance(ProcessSideQuestVictoryRewards::class, $victoryMock);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $this->assertEquals($experienceForMoments + (2 * $minionKillExperience) + $victoryExperience, $sideQuestResult->fresh()->experience_rewarded);
    }

    /**
     * @test
     */
    public function it_will_save_the_favor_rewarded_to_the_side_quest_result()
    {

        $finalMoment = rand(6, 50);
        $minionSnapshot = MinionSnapshotFactory::new()->create();
        /** @var ConvertMinionSnapshotIntoCombatant $convertToCombatant */
        $convertToCombatant = app(ConvertMinionSnapshotIntoCombatant::class);
        $minionCombatant1 = $convertToCombatant->execute($minionSnapshot);
        $minionSnapshot = MinionSnapshotFactory::new()->create();
        $minionCombatant2 = $convertToCombatant->execute($minionSnapshot);
        $heroKillsMinionEvent1 = SideQuestEventFactory::new()
            ->heroKillsMinion(null, null, $minionCombatant1)
            ->withMoment(5);
        $heroKillsMinionEvent2 = SideQuestEventFactory::new()
            ->heroKillsMinion(null, null, $minionCombatant2)
            ->withMoment(5);
        $victoryEvent = SideQuestEventFactory::new()->sideQuestVictory()->withMoment($finalMoment);

        $sideQuestResult = SideQuestResultFactory::new()
            ->combatProcessed()
            ->withEvents(collect([
                $heroKillsMinionEvent1,
                $heroKillsMinionEvent2,
                $victoryEvent
        ]))->create();

        $this->assertNull($sideQuestResult->favor_rewarded);

        $rewardMinionKillMock = \Mockery::mock(RewardSquadForMinionKill::class)
            ->shouldReceive('execute')
            ->times(2)
            ->andReturn([
                'experience' => 0,
                'favor' => $minionKillFavor = rand(1, 10)
            ])->getMock();

        $this->app->instance(RewardSquadForMinionKill::class, $rewardMinionKillMock);

        $victoryMock = \Mockery::mock(ProcessSideQuestVictoryRewards::class)
            ->shouldReceive('execute')
            ->andReturn([
                'experience' => 0,
                'favor' => $victoryFavor = rand(9, 99)
            ])->getMock();

        $this->app->instance(ProcessSideQuestVictoryRewards::class, $victoryMock);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $this->assertEquals( (2 * $minionKillFavor) + $victoryFavor, $sideQuestResult->fresh()->favor_rewarded);
    }
}
