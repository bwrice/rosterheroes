<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\SetupAllQuestsForNextWeek;
use App\Domain\Models\Quest;
use App\Factories\Models\QuestFactory;
use App\Jobs\FinalizeWeekJob;
use Bwrice\LaravelJobChainGroups\Jobs\AsyncChainedJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SetupAllQuestsForNextWeekTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_dispatch_setup_quest_jobs_chained_with_finalize_week()
    {
        $questOne = QuestFactory::new()->create();
        $questTwo = QuestFactory::new()->create();

        Queue::fake();

        $step = rand(1,6);
        $nextStep = $step + 1;

        /** @var SetupAllQuestsForNextWeek $domainAction */
        $domainAction = app(SetupAllQuestsForNextWeek::class);
        $domainAction->execute($step);
        foreach ([
                     $questOne,
                     $questTwo
                 ] as $quest) {

            /** @var Quest $quest */
            Queue::assertPushedWithChain(AsyncChainedJob::class, [
                new FinalizeWeekJob($nextStep)
            ], function (AsyncChainedJob $chainedJob) use ($quest) {
                return $chainedJob->getDecoratedJob()->quest->id === $quest->id;
            });
        }
    }
}
