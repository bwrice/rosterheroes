<?php

namespace Tests\Feature;

use App\Domain\Actions\RewardChestToSquad;
use App\Domain\Actions\RewardSquadForMinionKill;
use App\Domain\Models\Minion;
use App\Domain\Models\Squad;
use App\Factories\Models\ChestBlueprintFactory;
use App\Factories\Models\MinionFactory;
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

    /** @var Minion */
    protected $minion;

    public function setUp(): void
    {
        parent::setUp();
        $this->squad = SquadFactory::new()->create();
        $this->minion = MinionFactory::new()->create();
    }

    /**
     * @test
     */
    public function it_will_increase_the_squads_experience_by_the_minion_experience_reward()
    {
        $beginningExperience = $this->squad->experience;
        $experienceReward = $this->minion->getExperienceReward();
        $this->assertGreaterThan(0, $experienceReward);

        /** @var RewardSquadForMinionKill $domainAction */
        $domainAction = app(RewardSquadForMinionKill::class);
        $domainAction->execute($this->squad, $this->minion);

        $newExperience = $this->squad->fresh()->experience;
        $this->assertEquals($beginningExperience + $experienceReward, $newExperience);
    }

    /**
     * @test
     */
    public function it_will_execute_reward_chest_to_squad_actions_for_each_chest_blueprint_belonging_to_the_minion()
    {
        $chestBlueFactory = ChestBlueprintFactory::new();
        $this->minion->chestBlueprints()->save($chestBlueFactory->create());
        $this->minion->chestBlueprints()->save($chestBlueFactory->create());

        $rewardChestMock = \Mockery::mock(RewardChestToSquad::class)->shouldReceive('execute')->times(2)->getMock();
        app()->instance(RewardChestToSquad::class, $rewardChestMock);

        /** @var RewardSquadForMinionKill $domainAction */
        $domainAction = app(RewardSquadForMinionKill::class);
        $domainAction->execute($this->squad, $this->minion);
    }
}
