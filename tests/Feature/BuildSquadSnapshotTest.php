<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildHeroSnapshot;
use App\Domain\Actions\BuildSquadSnapshot;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Week;
use App\Facades\HeroService;
use App\Facades\WeekService;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\SquadSnapshotFactory;
use App\Helpers\EloquentMatcher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildSquadSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuildSquadSnapshot
     */
    protected function getDomainAction()
    {
        return app(BuildSquadSnapshot::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_is_an_existing_snapshot_for_the_squad_and_week()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $squad = SquadFactory::new()->create();
        $existingSnapshot = SquadSnapshotFactory::new()->withSquadID($squad->id)->withWeekID($week->id)->create();

        try {
            $this->getDomainAction()->execute($squad, $week);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildSquadSnapshot::EXCEPTION_CODE_SNAPSHOT_EXISTS, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_week_is_not_past_finalizing()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $finalizingStartsAt = WeekService::finalizingStartsAt($week->adventuring_locks_at);
        Date::setTestNow($finalizingStartsAt->subHour());

        $squad = SquadFactory::new()->create();

        try {
            $this->getDomainAction()->execute($squad, $week);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildSquadSnapshot::EXCEPTION_CODE_WEEK_NOT_FINALIZING, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_create_a_squad_snapshot()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $finalizingStartsAt = WeekService::finalizingStartsAt($week->adventuring_locks_at);
        Date::setTestNow($finalizingStartsAt->addHour());
        $squad = SquadFactory::new()->create();
        $squadSnapshot = $this->getDomainAction()->execute($squad, $week);

        $this->assertEquals($squad->id, $squadSnapshot->squad_id);
        $this->assertEquals($week->id, $squadSnapshot->week_id);
        $this->assertEquals($squad->experience, $squadSnapshot->experience);
        $this->assertEquals($squad->squad_rank_id, $squadSnapshot->squad_rank_id);
    }

    /**
     * @test
     */
    public function it_will_execute_build_hero_snapshots_for_ready_heroes()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $finalizingStartsAt = WeekService::finalizingStartsAt($week->adventuring_locks_at);
        Date::setTestNow($finalizingStartsAt->addHour());
        $squad = SquadFactory::new()->create();

        /** @var Hero $heroOne */
        $heroOne = HeroFactory::new()->forSquad($squad)->create();
        $heroTwo = HeroFactory::new()->forSquad($squad)->create();

        HeroService::partialMock()->shouldReceive('combatReady')->andReturnUsing(function (Hero $hero) use ($heroTwo) {
            if ($hero->id === $heroTwo->id) {
                return true;
            }
            return false;
        });

        $mock = $this->getMockBuilder(BuildHeroSnapshot::class)->getMock();
        $mock->expects($this->once())->method('execute')->with($this->callback(function (SquadSnapshot $squadSnapshot) use ($squad) {
            return $squadSnapshot->squad_id === $squad->id;
        }), $this->callback(function (Hero $hero) use ($heroTwo) {
            return $hero->id === $heroTwo->id;
        }));

        $this->instance(BuildHeroSnapshot::class, $mock);

        $this->getDomainAction()->execute($squad, $week);
    }
}
