<?php

namespace Tests\Feature;

use App\Domain\Actions\NPC\AutoManageNPC;
use App\Domain\Actions\NPC\DispatchAutoManageNPCJobs;
use App\Domain\Models\User;
use App\Facades\CurrentWeek;
use App\Facades\NPC;
use App\Factories\Models\SquadFactory;
use App\Jobs\AutoManageNPCJob;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DispatchAutoManageNPCJobsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return DispatchAutoManageNPCJobs
     */
    protected function getDomainAction()
    {
        return app(DispatchAutoManageNPCJobs::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_auto_manage_jobs_for_npc_squads()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        NPC::shouldReceive('user')->andReturn($user);
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);

        $count = rand(2, 4);
        $squadFactory = SquadFactory::new()->forUser($user);
        $npcSquads = collect();
        for ($i = 1; $i <= $count; $i++) {
            $npcSquads->push($squadFactory->create());
        }

        Queue::fake();

        $this->getDomainAction()->execute();

        Queue::assertPushed(AutoManageNPCJob::class, $count);

        $squadIDs = $npcSquads->pluck('id')->toArray();

        Queue::assertPushed(AutoManageNPCJob::class, function (AutoManageNPCJob $job) use ($squadIDs) {
            return in_array($job->npc->id, $squadIDs);
        });
    }

    /**
     * @test
     */
    public function it_will_dispatch_auto_manage_jobs_with_low_trigger_chance_overnight_during_the_week()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        NPC::shouldReceive('user')->andReturn($user);
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);

        SquadFactory::new()->forUser($user)->create();

        /** @var CarbonInterface $testNow */
        $testNow = now()->setTimezone('America/New_York')
            ->weekday(3)
            ->setHour(3);
        Date::setTestNow($testNow);


        Queue::fake();

        $this->getDomainAction()->execute();

        Queue::assertPushed(AutoManageNPCJob::class, function (AutoManageNPCJob $job) {
            return $job->triggerChance < 10 && $job->triggerChance > 0;
        });
    }

    /**
     * @test
     */
    public function it_will_dispatch_auto_manage_jobs_with_high_trigger_chance_on_monday_evenings()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        NPC::shouldReceive('user')->andReturn($user);
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);

        SquadFactory::new()->forUser($user)->create();

        /** @var CarbonInterface $testNow */
        $testNow = now()->setTimezone('America/New_York')
            ->weekday(1)
            ->setHour(18);
        Date::setTestNow($testNow);


        Queue::fake();

        $this->getDomainAction()->execute();

        Queue::assertPushed(AutoManageNPCJob::class, function (AutoManageNPCJob $job) {
            return $job->triggerChance > 10 && $job->triggerChance < 80;
        });
    }

    /**
     * @test
     */
    public function it_will_pass_null_for_default_actions_if_week_is_not_locked()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        NPC::shouldReceive('user')->andReturn($user);
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(false);

        SquadFactory::new()->forUser($user)->create();

        Queue::fake();

        $this->getDomainAction()->execute();

        Queue::assertPushed(AutoManageNPCJob::class, function (AutoManageNPCJob $job) {
            return is_null($job->actions);
        });
    }

    /**
     * @test
     */
    public function it_will_pass_only_the_adventuring_open_actions_if_week_adventuring_locked()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        NPC::shouldReceive('user')->andReturn($user);
        CurrentWeek::shouldReceive('adventuringLocked')->andReturn(true); // What we're testing

        SquadFactory::new()->forUser($user)->create();

        Queue::fake();

        $this->getDomainAction()->execute();

        Queue::assertPushed(AutoManageNPCJob::class, function (AutoManageNPCJob $job) {
            return $job->actions === AutoManageNPC::ADVENTURING_LOCKED_ACTIONS;
        });
    }
}
