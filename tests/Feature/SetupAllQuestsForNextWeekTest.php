<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\SetupAllQuestsForNextWeek;
use App\Factories\Models\QuestFactory;
use App\Jobs\SetupQuestForNextWeekJob;
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

            Queue::assertPushed(function (SetupQuestForNextWeekJob $job) use ($quest) {
                return $job->quest->id === $quest->id;
            });
        }
    }
}
