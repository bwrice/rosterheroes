<?php

namespace Tests\Feature;

use App\Domain\Actions\ProcessSideQuestRewards;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestResultFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class ProcessSideQuestRewardsTest extends TestCase
{
    use DatabaseTransactions;

    /** @var \App\SideQuestResult */
    protected $sideQuestResult;

    public function setUp(): void
    {
        parent::setUp();
        $this->sideQuestResult = SideQuestResultFactory::new()->create();
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_side_quest_rewards_already_processed()
    {
        $this->sideQuestResult->rewards_processed_at = Date::now();
        $this->sideQuestResult->save();
        $originalSquadExperience = $this->sideQuestResult->squad->experience;

        try {
            /** @var ProcessSideQuestRewards $domainAction */
            $domainAction = app(ProcessSideQuestRewards::class);
            $domainAction->execute($this->sideQuestResult->fresh());
        } catch (\Exception $exception) {
            $squad = $this->sideQuestResult->squad->fresh();
            $this->assertEquals($originalSquadExperience, $squad->experience);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_update_the_rewards_processed_at_on_the_side_quest_result()
    {
        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($this->sideQuestResult);
        $this->assertNotNull($this->sideQuestResult->fresh()->rewards_processed_at);
    }

    /**
     * @test
     */
    public function it_will_increase_the_squads_experience_by_the_side_quest_experience_reward()
    {
        $sideQuestFactory = SideQuestFactory::new()->withMinions();
        $sideQuestResult = SideQuestResultFactory::new()->withSideQuest($sideQuestFactory)->create();
        $squad = $sideQuestResult->squad;
        $originalSquadExperience = $squad->experience;

        $sideQuestXpReward = $sideQuestResult->sideQuest->getExperienceReward();
        $this->assertGreaterThan(0, $sideQuestXpReward);

        /** @var ProcessSideQuestRewards $domainAction */
        $domainAction = app(ProcessSideQuestRewards::class);
        $domainAction->execute($sideQuestResult);

        $this->assertEquals($originalSquadExperience + $sideQuestXpReward, $squad->fresh()->experience);
    }
}
