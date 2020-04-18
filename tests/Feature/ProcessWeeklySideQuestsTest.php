<?php


namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestCombat;
use App\Domain\Actions\WeekFinalizing\ProcessWeeklySideQuestsAction;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Jobs\FinalizeWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

abstract class ProcessWeeklySideQuestsTest extends TestCase
{
    /**
    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
    }

    abstract protected function getBaseSideQuestResultFactory(): SideQuestResultFactory;

    abstract protected function getDomainAction(): ProcessWeeklySideQuestsAction;

    /**
     * @test
     */
    public function it_will_queue_process_side_quest_result_jobs_chained_with_finalize_week_next_step()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);

        $campaignStop1 = $campaignStopFactory->create();
        $campaignStop2 = $campaignStopFactory->create();
        $campaignStop3 = $campaignStopFactory->create();

        $sideQuest1 = SideQuestFactory::new()->create();
        $sideQuest2 = SideQuestFactory::new()->create();

        $baseSideQuestResultFactory = $this->getBaseSideQuestResultFactory();

        $sideQuestResult1 = $baseSideQuestResultFactory->create([
            'campaign_stop_id' => $campaignStop1->id,
            'side_quest_id' => $sideQuest1->id,
        ]);
        $sideQuestResult2 = $baseSideQuestResultFactory->create([
            'campaign_stop_id' => $campaignStop1->id,
            'side_quest_id' => $sideQuest2->id,
        ]);
        $sideQuestResult3 = $baseSideQuestResultFactory->create([
            'campaign_stop_id' => $campaignStop2->id,
            'side_quest_id' => $sideQuest1->id,
        ]);
        $sideQuestResult4 = $baseSideQuestResultFactory->create([
            'campaign_stop_id' => $campaignStop3->id,
            'side_quest_id' => $sideQuest2->id,
        ]);

        $step = rand(1, 4);
        $nextStep = $step + 1;

        Queue::fake();

        $domainAction = $this->getDomainAction();
        $domainAction->execute($step);

        foreach ([
                     $sideQuestResult1,
                     $sideQuestResult2,
                     $sideQuestResult3,
                     $sideQuestResult4
                 ] as $sideQuestResult) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($sideQuestResult) {
                return $chainedJob->getDecoratedJob()->sideQuestResult->id === $sideQuestResult->id;
            });
        }
    }

    /**
     * @test
     */
    public function it_wont_dispatch_campaign_stops_for_the_non_current_week()
    {
        $validCampaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $validCampaignStopFactory = CampaignStopFactory::new()->withCampaign($validCampaignFactory);

        $validCampaignStop = $validCampaignStopFactory->create();

        $invalidCampaignFactory = CampaignFactory::new()->withWeekID(factory(Week::class)->create()->id);
        $invalidCampaignStopFactory = CampaignStopFactory::new()->withCampaign($invalidCampaignFactory);

        $invalidCampaignStop = $invalidCampaignStopFactory->create();

        $baseSideQuestResultFactory = $this->getBaseSideQuestResultFactory();
        $sideQuest = SideQuestFactory::new()->create();

        $validSideQuestResult = $baseSideQuestResultFactory->create([
            'campaign_stop_id' => $validCampaignStop->id,
            'side_quest_id' => $sideQuest->id,
        ]);
        $invalidSideQuestResult = $baseSideQuestResultFactory->create([
            'campaign_stop_id' => $invalidCampaignStop->id,
            'side_quest_id' => $sideQuest->id,
        ]);

        $step = rand(1, 4);
        Queue::fake();


        $domainAction = $this->getDomainAction();
        $domainAction->execute($step);

        Queue::assertPushed(AsyncChainedJob::class, function (AsyncChainedJob $chainedJob) use ($validSideQuestResult) {
            return $chainedJob->getDecoratedJob()->sideQuestResult->id === $validSideQuestResult->id;
        });

        Queue::assertNotPushed(AsyncChainedJob::class, function (AsyncChainedJob $chainedJob) use ($invalidSideQuestResult) {
            return $chainedJob->getDecoratedJob()->sideQuestResult->id === $invalidSideQuestResult->id;
        });
    }

    /**
     * @test
     */
    public function it_will_dispatch_finalize_week_with_same_step_and_with_last_side_quest_result_id_if_max_jobs_reached()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);
        $sideQuestResultFactory = $this->getBaseSideQuestResultFactory()->withCampaignStop($campaignStopFactory);

        $sideQuestResult1 = $sideQuestResultFactory->create();
        $sideQuestResult2 = $sideQuestResultFactory->create();
        $sideQuestResult3 = $sideQuestResultFactory->create();

        $originalStep = rand(1, 4);
        Queue::fake();


        $domainAction = $this->getDomainAction();
        $domainAction->setMaxSideQuestResults(2); // set to 2 because we have 3 campaign stops
        $domainAction->execute($originalStep);

        $extra = [
            ProcessWeeklySideQuestsAction::EXTRA_LAST_SIDE_QUEST_RESULT_KEY => $sideQuestResult2->id
        ];

        foreach ([
                     $sideQuestResult1,
                     $sideQuestResult2
                 ] as $sideQuestResult) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($originalStep, $extra)
            ], function (AsyncChainedJob $chainedJob) use ($sideQuestResult) {
                return $chainedJob->getDecoratedJob()->sideQuestResult->id === $sideQuestResult->id;
            });
        }

        Queue::assertNotPushed(AsyncChainedJob::class, function (AsyncChainedJob $chainedJob) use ($sideQuestResult3) {
            return $chainedJob->getDecoratedJob()->sideQuestResult->id === $sideQuestResult3->id;
        });
    }

    /**
     * @test
     */
    public function it_will_increase_next_step_job_without_extra_data_if_exactly_max_side_quest_results_needed_for_processing()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);
        $sideQuestResultFactory = $this->getBaseSideQuestResultFactory()->withCampaignStop($campaignStopFactory);

        $sideQuestResult1 = $sideQuestResultFactory->create();
        $sideQuestResult2 = $sideQuestResultFactory->create();
        $sideQuestResult3 = $sideQuestResultFactory->create();

        $originalStep = rand(1, 4);
        $nextStep = $originalStep + 1;
        Queue::fake();


        $domainAction = $this->getDomainAction();
        $domainAction->setMaxSideQuestResults(3); // set to 3 for exact amount of stops we have
        $domainAction->execute($originalStep);

        foreach ([
                     $sideQuestResult1,
                     $sideQuestResult2,
                     $sideQuestResult3
                 ] as $sideQuestResult) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($sideQuestResult) {
                return $chainedJob->getDecoratedJob()->sideQuestResult->id === $sideQuestResult->id;
            });
        }
    }

    /**
     * @test
     */
    public function it_will_only_query_side_quest_results_with_ids_greater_than_that_given_in_the_extra_data_array()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);
        $sideQuestResultFactory = $this->getBaseSideQuestResultFactory()->withCampaignStop($campaignStopFactory);

        $sideQuestResult1 = $sideQuestResultFactory->create();
        $sideQuestResult2 = $sideQuestResultFactory->create();
        $sideQuestResult3 = $sideQuestResultFactory->create();

        $step = rand(1, 4);
        $nextStep = $step + 1;
        Queue::fake();


        $domainAction = $this->getDomainAction();
        $domainAction->execute($step, [
            ProcessWeeklySideQuestCombat::EXTRA_LAST_SIDE_QUEST_RESULT_KEY => $sideQuestResult2->id
        ]);

        Queue::assertPushedWithChain(AsyncChainedJob::class, [
            new FinalizeWeekJob($nextStep)
        ], function (AsyncChainedJob $chainedJob) use ($sideQuestResult3) {
            return $chainedJob->getDecoratedJob()->sideQuestResult->id === $sideQuestResult3->id;
        });

        foreach ([
                     $sideQuestResult1,
                     $sideQuestResult2
                 ] as $sideQuestResult) {

            Queue::assertNotPushed(AsyncChainedJob::class, function (AsyncChainedJob $chainedJob) use ($sideQuestResult) {
                return $chainedJob->getDecoratedJob()->sideQuestResult->id === $sideQuestResult->id;
            });
        }
    }
}
