<?php

namespace Tests\Feature;

use App\Domain\Actions\SetupNextWeekAction;
use App\Domain\Actions\WeekFinalizing\FinalizeWeekFinalStep;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Jobs\ProcessSideQuestRewardsJob;
use App\SideQuestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FinalizeWeekFinalStepTest extends TestCase
{
    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->state('as-current')->create();
    }

    /**
     * @test
     */
    public function it_will_execute_set_up_next_week_action()
    {
        $setupNextWeekSpy = \Mockery::spy(SetupNextWeekAction::class);

        app()->instance(SetupNextWeekAction::class, $setupNextWeekSpy);

        $nextStep = rand(1,5);
        /** @var FinalizeWeekFinalStep $domainAction */
        $domainAction = app(FinalizeWeekFinalStep::class);
        $domainAction->execute($nextStep);

        $setupNextWeekSpy->shouldHaveReceived('execute');
    }


    /**
     * @test
     */
    public function it_will_dispatch_process_side_quest_reward_jobs_for_the_current_week()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);
        $sideQuestResultFactory = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory);

        $sideQuestResultOne = $sideQuestResultFactory->create();
        $sideQuestResultTwo = $sideQuestResultFactory->create();
        $sideQuestResultThree = $sideQuestResultFactory->create();

        foreach([
            $sideQuestResultOne,
            $sideQuestResultTwo,
            $sideQuestResultThree
                ] as $sideQuestResult) {
            /** @var SideQuestResult $sideQuestResult */
            $this->assertNull($sideQuestResult->rewards_processed_at);
        }

        Queue::fake();

        $nextStep = rand(1,5);
        /** @var FinalizeWeekFinalStep $domainAction */
        $domainAction = app(FinalizeWeekFinalStep::class);
        $domainAction->execute($nextStep);


        foreach([
                    $sideQuestResultOne,
                    $sideQuestResultTwo,
                    $sideQuestResultThree
                ] as $sideQuestResult) {

            /** @var SideQuestResult $sideQuestResult */
            Queue::assertPushed(ProcessSideQuestRewardsJob::class, function (ProcessSideQuestRewardsJob $job) use ($sideQuestResult) {
                return $sideQuestResult->id === $job->sideQuestResult->id;
            });
        }
    }
}
