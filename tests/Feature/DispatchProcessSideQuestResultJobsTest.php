<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\DispatchProcessSideQuestResultJobs;
use App\Domain\Models\SideQuestResult;
use App\Domain\Models\Week;
use App\Factories\Models\CampaignFactory;
use App\Factories\Models\CampaignStopFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Jobs\ProcessCombatForSideQuestResultJob;
use App\Jobs\ProcessSideQuestRewardsJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DispatchProcessSideQuestResultJobsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return DispatchProcessSideQuestResultJobs
     */
    protected function getDomainAction()
    {
        return app(DispatchProcessSideQuestResultJobs::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_process_side_quest_result_jobs_for_current_week_side_quests_in_chain()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withWeekID($currentWeek->id));
        $sideQuestResultFactory = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory);
        $sqResult1 = $sideQuestResultFactory->create();
        $sqResult2 = $sideQuestResultFactory->create();

        Queue::fake();

        $this->getDomainAction()->execute();

        foreach ([$sqResult1, $sqResult2] as $sqResult) {

            Queue::assertPushed(function (ProcessCombatForSideQuestResultJob $job) use ($sqResult) {
                return $job->sideQuestResult->id === $sqResult->id;
            });

            Queue::assertPushedWithChain(ProcessCombatForSideQuestResultJob::class, [
                new ProcessSideQuestRewardsJob($sqResult)
            ]);
        }
    }

    /**
     * @test
     */
    public function it_will_not_dispatch_process_side_quest_result_jobs_for_side_results_for_non_current_weeks()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withWeekID(factory(Week::class)->create()->id));
        $sideQuestResult = SideQuestResultFactory::new()->withCampaignStop($campaignStopFactory)->create();


        Queue::fake();

        $this->getDomainAction()->execute();

        Queue::assertNotPushed(function (ProcessCombatForSideQuestResultJob $job) use ($sideQuestResult) {
            return $job->sideQuestResult->id === $sideQuestResult->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_dispatch_process_side_quest_result_jobs_for_side_results_already_processed()
    {
        $currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
        $campaignStopFactory = CampaignStopFactory::new()->withCampaign(CampaignFactory::new()->withWeekID($currentWeek->id));
        $sideQuestResult = SideQuestResultFactory::new()
            ->withCampaignStop($campaignStopFactory)
            ->combatProcessed()
            ->create();


        Queue::fake();

        $this->getDomainAction()->execute();

        Queue::assertNotPushed(function (ProcessCombatForSideQuestResultJob $job) use ($sideQuestResult) {
            return $job->sideQuestResult->id === $sideQuestResult->id;
        });
    }
}
