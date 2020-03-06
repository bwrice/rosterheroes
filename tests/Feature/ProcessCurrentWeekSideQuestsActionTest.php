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
}
