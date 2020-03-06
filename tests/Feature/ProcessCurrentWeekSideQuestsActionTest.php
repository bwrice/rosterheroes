<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\ProcessCurrentWeekSideQuestsAction;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SquadFactory;
use App\Jobs\FinalizeWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessCurrentWeekSideQuestsActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
    }

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

        $campaignStop1->sideQuests()->save($sideQuest1);
        $campaignStop1->sideQuests()->save($sideQuest2);

        $campaignStop2->sideQuests()->save($sideQuest1);

        $campaignStop3->sideQuests()->save($sideQuest2);

        $step = rand(1, 10);
        $nextStep = $step + 1;

        Queue::fake();

        /** @var ProcessCurrentWeekSideQuestsAction $domainAction */
        $domainAction = app(ProcessCurrentWeekSideQuestsAction::class);
        $domainAction->execute($step);

        foreach ([
                     $campaignStop1,
                     $campaignStop2
                 ] as $campaignStop) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($sideQuest1, $campaignStop) {
                return $chainedJob->getDecoratedJob()->getSideQuest()->id === $sideQuest1->id
                    && $chainedJob->getDecoratedJob()->getCampaignStop()->id === $campaignStop->id;
            });
        }

        foreach ([
                     $campaignStop1,
                     $campaignStop3
                 ] as $campaignStop) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($sideQuest2, $campaignStop) {
                return $chainedJob->getDecoratedJob()->getSideQuest()->id === $sideQuest2->id
                    && $chainedJob->getDecoratedJob()->getCampaignStop()->id === $campaignStop->id;
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

        $sideQuest = SideQuestFactory::new()->create();

        $validCampaignStop->sideQuests()->save($sideQuest);
        $invalidCampaignStop->sideQuests()->save($sideQuest);

        $step = rand(1, 10);
        Queue::fake();

        /** @var ProcessCurrentWeekSideQuestsAction $domainAction */
        $domainAction = app(ProcessCurrentWeekSideQuestsAction::class);
        $domainAction->execute($step);

        Queue::assertPushed(AsyncChainedJob::class, function (AsyncChainedJob $chainedJob) use ($validCampaignStop) {
            return $chainedJob->getDecoratedJob()->getCampaignStop()->id === $validCampaignStop->id;
        });

        Queue::assertNotPushed(AsyncChainedJob::class, function (AsyncChainedJob $chainedJob) use ($invalidCampaignStop) {
            return $chainedJob->getDecoratedJob()->getCampaignStop()->id === $invalidCampaignStop->id;
        });
    }

    /**
     * @test
     */
    public function it_will_dispatch_finalize_week_with_same_step_and_with_last_campaign_stop_id_if_max_jobs_reached()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);

        $campaignStop1 = $campaignStopFactory->create();
        $campaignStop2 = $campaignStopFactory->create();
        $campaignStop3 = $campaignStopFactory->create();

        $sideQuest = SideQuestFactory::new()->create();

        $campaignStop1->sideQuests()->save($sideQuest);
        $campaignStop2->sideQuests()->save($sideQuest);
        $campaignStop3->sideQuests()->save($sideQuest);

        $originalStep = rand(1, 10);
        Queue::fake();

        /** @var ProcessCurrentWeekSideQuestsAction $domainAction */
        $domainAction = app(ProcessCurrentWeekSideQuestsAction::class);
        $domainAction->setMaxCampaignStopsQueried(2); // set to 2 because we have 3 campaign stops
        $domainAction->execute($originalStep);

        $extra = [
            'last_campaign_stop_id' => $campaignStop2->id
        ];

        foreach ([
                     $campaignStop1,
                     $campaignStop2
                 ] as $campaignStop) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($originalStep, $extra)
            ], function (AsyncChainedJob $chainedJob) use ($sideQuest, $campaignStop) {
                return $chainedJob->getDecoratedJob()->getSideQuest()->id === $sideQuest->id
                    && $chainedJob->getDecoratedJob()->getCampaignStop()->id === $campaignStop->id;
            });
        }
    }

    /**
     * @test
     */
    public function it_will_increase_next_step_job_without_extra_data_if_exactly_max_stops_needed_for_processing()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);

        $campaignStop1 = $campaignStopFactory->create();
        $campaignStop2 = $campaignStopFactory->create();
        $campaignStop3 = $campaignStopFactory->create();

        $sideQuest = SideQuestFactory::new()->create();

        $campaignStop1->sideQuests()->save($sideQuest);
        $campaignStop2->sideQuests()->save($sideQuest);
        $campaignStop3->sideQuests()->save($sideQuest);

        $originalStep = rand(1, 10);
        $nextStep = $originalStep + 1;
        Queue::fake();

        /** @var ProcessCurrentWeekSideQuestsAction $domainAction */
        $domainAction = app(ProcessCurrentWeekSideQuestsAction::class);
        $domainAction->setMaxCampaignStopsQueried(3); // set to 3 for exact amount of stops we have
        $domainAction->execute($originalStep);

        foreach ([
                     $campaignStop1,
                     $campaignStop2,
                     $campaignStop3
                 ] as $campaignStop) {

            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($sideQuest, $campaignStop) {
                return $chainedJob->getDecoratedJob()->getSideQuest()->id === $sideQuest->id
                    && $chainedJob->getDecoratedJob()->getCampaignStop()->id === $campaignStop->id;
            });
        }
    }

    /**
     * @test
     */
    public function it_will_only_query_campaign_stops_with_ids_greater_than_that_given_in_the_extra_data_array()
    {
        $campaignFactory = CampaignFactory::new()->withWeekID($this->currentWeek->id);
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign($campaignFactory);

        $campaignStop1 = $campaignStopFactory->create();
        $campaignStop2 = $campaignStopFactory->create();
        $campaignStop3 = $campaignStopFactory->create();

        $sideQuest = SideQuestFactory::new()->create();

        $campaignStop1->sideQuests()->save($sideQuest);
        $campaignStop2->sideQuests()->save($sideQuest);
        $campaignStop3->sideQuests()->save($sideQuest);

        $step = rand(1, 10);
        $nextStep = $step + 1;
        Queue::fake();

        /** @var ProcessCurrentWeekSideQuestsAction $domainAction */
        $domainAction = app(ProcessCurrentWeekSideQuestsAction::class);
        $domainAction->execute($step, [
            'last_campaign_stop_id' => $campaignStop2->id
        ]);

        Queue::assertPushedWithChain(AsyncChainedJob::class, [
            new FinalizeWeekJob($nextStep)
        ], function (AsyncChainedJob $chainedJob) use ($sideQuest, $campaignStop3) {
            return $chainedJob->getDecoratedJob()->getSideQuest()->id === $sideQuest->id
                && $chainedJob->getDecoratedJob()->getCampaignStop()->id === $campaignStop3->id;
        });

        foreach ([
                     $campaignStop1,
                     $campaignStop2
                 ] as $campaignStop) {

            Queue::assertNotPushed(AsyncChainedJob::class, function (AsyncChainedJob $chainedJob) use ($sideQuest, $campaignStop) {
                return $chainedJob->getDecoratedJob()->getSideQuest()->id === $sideQuest->id
                    && $chainedJob->getDecoratedJob()->getCampaignStop()->id === $campaignStop->id;
            });
        }
    }
}
