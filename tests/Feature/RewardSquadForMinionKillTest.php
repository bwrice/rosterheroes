<?php

namespace Tests\Feature;

use App\Domain\Actions\RewardChestToSquad;
use App\Domain\Actions\RewardSquadForMinionKill;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\Squad;
use App\Factories\Models\ChestBlueprintFactory;
use App\Factories\Models\MinionSnapshotFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RewardSquadForMinionKillTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var MinionSnapshot */
    protected $minionSnapshot;

    public function setUp(): void
    {
        parent::setUp();
        $this->squad = SquadFactory::new()->create();
        $this->minionSnapshot = MinionSnapshotFactory::new()->create();
    }

    /**
     * @test
     */
    public function it_will_increase_the_squads_experience_by_the_minion_experience_reward()
    {
        $beginningExperience = $this->squad->experience;
        $experienceReward = $this->minionSnapshot->experience_reward;
        $this->assertGreaterThan(0, $experienceReward);

        /** @var RewardSquadForMinionKill $domainAction */
        $domainAction = app(RewardSquadForMinionKill::class);
        $domainAction->execute($this->squad, $this->minionSnapshot);

        $newExperience = $this->squad->fresh()->experience;
        $this->assertEquals($beginningExperience + $experienceReward, $newExperience);
    }

    /**
     * @test
     */
    public function it_will_increase_the_squads_favor_by_the_minions_experience_reward()
    {
        // make sure minion level is high enough that we get more than 1 point in favor
        $this->minionSnapshot->level = rand(50, 200);
        $this->minionSnapshot->save();
        $this->minionSnapshot = $this->minionSnapshot->fresh();

        $beginningFavor = $this->squad->favor;
        $FavorReward = $this->minionSnapshot->favor_reward;
        $this->assertGreaterThan(0, $FavorReward);

        /** @var RewardSquadForMinionKill $domainAction */
        $domainAction = app(RewardSquadForMinionKill::class);
        $domainAction->execute($this->squad, $this->minionSnapshot);

        $newFavor = $this->squad->fresh()->favor;
        $this->assertEquals($beginningFavor + $FavorReward, $newFavor);
    }

    /**
     * @test
     */
    public function it_will_execute_reward_chest_to_squad_actions_for_each_chest_blueprint_belonging_to_the_minion()
    {
        $chestBlueFactory = ChestBlueprintFactory::new();
        $this->minionSnapshot->chestBlueprints()->save($chestBlueFactory->create(), [
            'count' => 1,
            'chance' => 100 // make chance 100 to guarantee rewarded
        ]);
        $this->minionSnapshot->chestBlueprints()->save($chestBlueFactory->create(), [
            'count' => 1,
            'chance' => 100 // make chance 100 to guarantee rewarded
        ]);

        $rewardChestMock = \Mockery::mock(RewardChestToSquad::class)->shouldReceive('execute')->times(2)->getMock();
        app()->instance(RewardChestToSquad::class, $rewardChestMock);

        /** @var RewardSquadForMinionKill $domainAction */
        $domainAction = app(RewardSquadForMinionKill::class);
        $domainAction->execute($this->squad, $this->minionSnapshot);
    }

    /**
     * @test
     */
    public function it_will_reward_chests_based_on_the_pivot_count()
    {
        $chestBlueFactory = ChestBlueprintFactory::new();
        $this->minionSnapshot->chestBlueprints()->save($chestBlueFactory->create(), [
            'count' => 3,
            'chance' => 100 // make chance 100 to guarantee rewarded
        ]);

        $rewardChestMock = \Mockery::mock(RewardChestToSquad::class)->shouldReceive('execute')->times(3)->getMock();
        app()->instance(RewardChestToSquad::class, $rewardChestMock);

        /** @var RewardSquadForMinionKill $domainAction */
        $domainAction = app(RewardSquadForMinionKill::class);
        $domainAction->execute($this->squad, $this->minionSnapshot);
    }

    /**
     * @test
     */
    public function it_will_not_reward_a_zero_chance_chest()
    {

        $chestBlueFactory = ChestBlueprintFactory::new();
        $this->minionSnapshot->chestBlueprints()->save($chestBlueFactory->create(), [
            'count' => 1,
            'chance' => 0
        ]);

        $rewardChestMock = \Mockery::mock(RewardChestToSquad::class)->shouldReceive('execute')->times(0)->getMock();
        app()->instance(RewardChestToSquad::class, $rewardChestMock);

        /** @var RewardSquadForMinionKill $domainAction */
        $domainAction = app(RewardSquadForMinionKill::class);
        $domainAction->execute($this->squad, $this->minionSnapshot);
    }
}
