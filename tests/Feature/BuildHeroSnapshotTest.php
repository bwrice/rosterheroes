<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildHeroSnapshot;
use App\Domain\Models\Hero;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\SquadSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildHeroSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    /** @var SquadSnapshot */
    protected $squadSnapshot;

    /** @var Hero */
    protected $hero;

    public function setUp(): void
    {
        parent::setUp();

        $this->currentWeek = factory(Week::class)->states('as-current')->create();
        $this->squadSnapshot = SquadSnapshotFactory::new()->withWeekID($this->currentWeek->id)->create();
        $this->hero = HeroFactory::new()->withSquadID($this->squadSnapshot->squad_id)->create();

        Date::setTestNow(WeekService::finalizingStartsAt($this->currentWeek->adventuring_locks_at)->addHour());
    }


    /**
     * @return BuildHeroSnapshot
     */
    protected function getDomainAction()
    {
        return app(BuildHeroSnapshot::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_current_week_is_not_the_squad_snapshot_week()
    {
        $newCurrentWeek = factory(Week::class)->states('as-current')->create();

        try {
            $this->getDomainAction()->execute($this->squadSnapshot, $this->hero);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildHeroSnapshot::EXCEPTION_CODE_SNAPSHOT_WEEK_NOT_CURRENT, $exception->getCode());
            return;
        }

        $this->fail("exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_hero_does_belong_to_the_squad_snapshots_squad()
    {

    }
}
