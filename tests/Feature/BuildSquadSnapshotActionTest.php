<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildHeroSnapshotAction;
use App\Domain\Actions\BuildSquadSnapshotAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\BuildSquadSnapshotException;
use App\Facades\CurrentWeek;
use App\Facades\HeroCombat;
use App\Domain\Models\SquadSnapshot;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildSquadSnapshotActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Hero */
    protected $heroOne;

    /** @var Hero */
    protected $heroTwo;

    /** @var Hero */
    protected $heroThree;

    /** @var Week */
    protected $week;

    /** @var BuildSquadSnapshotAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->week = factory(Week::class)->state('as-current')->create();
        $this->squad = factory(Squad::class)->create();
        $this->heroOne = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);
        $this->heroTwo = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);
        $this->heroThree = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);

        $this->domainAction = app(BuildSquadSnapshotAction::class);
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_current_week_is_not_ready_for_finalizing()
    {
        CurrentWeek::partialMock()->shouldReceive('finalizing')->andReturn(false);
        HeroCombat::partialMock()->shouldReceive('ready')->andReturn(true);
        try {
            $this->domainAction->execute($this->squad->fresh());
        } catch (BuildSquadSnapshotException $exception) {
            $this->assertEquals(BuildSquadSnapshotException::CODE_WEEK_NOT_FINALIZED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_create_a_squad_snapshot()
    {
        CurrentWeek::partialMock()->shouldReceive('finalizing')->andReturn(true);
        HeroCombat::partialMock()->shouldReceive('ready')->andReturn(true);
        // execute should only be called once for heroOne
        $mock = \Mockery::mock(BuildHeroSnapshotAction::class)->shouldReceive('execute')->times(3)->getMock();
        app()->instance(BuildHeroSnapshotAction::class, $mock);
        // We have to pull it back out of the container to use our mocked BuildHeroSnapshotAction
        /** @var BuildSquadSnapshotAction $domainAction */
        $domainAction = app(BuildSquadSnapshotAction::class);
        $squadSnapshot = $domainAction->execute($this->squad);
        $this->assertEquals($this->squad->id, $squadSnapshot->squad_id);
        $this->assertEquals($this->week->id, $squadSnapshot->week_id);
    }

    /**
    * @test
    */
    public function it_will_execute_build_hero_snapshot_action_for_combat_ready_heroes()
    {
        CurrentWeek::partialMock()->shouldReceive('finalizing')->andReturn(true);
        // Mock only heroOne to be combat ready
        HeroCombat::partialMock()->shouldReceive('ready')->times(3)->andReturnUsing(function (Hero $hero) {
            if ($hero->id === $this->heroOne->id) {
                return true;
            } else {
                return false;
            }
        });
        // execute should only be called once for heroOne
        $mock = \Mockery::mock(BuildHeroSnapshotAction::class)->shouldReceive('execute')->times(1)->getMock();
        app()->instance(BuildHeroSnapshotAction::class, $mock);
        // We have to pull it back out of the container to use our mocked BuildHeroSnapshotAction
        /** @var BuildSquadSnapshotAction $domainAction */
        $domainAction = app(BuildSquadSnapshotAction::class);
        $domainAction->execute($this->squad);
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_no_heroes_are_combat_ready()
    {
        CurrentWeek::partialMock()->shouldReceive('finalizing')->andReturn(true);
        HeroCombat::partialMock()->shouldReceive('ready')->andReturn(false);
        try {
            $this->domainAction->execute($this->squad);
        } catch (BuildSquadSnapshotException $exception) {
            $this->assertEquals(BuildSquadSnapshotException::CODE_NO_COMBAT_READY_HEROES, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
