<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildHeroSnapshotAction;
use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\BuildHeroSnapshotException;
use App\SquadSnapshot;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildHeroSnapshotActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Hero */
    protected $hero;

    /** @var SquadSnapshot */
    protected $squadSnapshot;

    /** @var PlayerSpirit */
    protected $playerSpirit;

    /** @var Game */
    protected $game;

    /** @var Week */
    protected $week;

    /** @var BuildHeroSnapshotAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        Week::setTestCurrent($this->week);
        $this->squad = factory(Squad::class)->create();
        $this->squadSnapshot = factory(SquadSnapshot::class)->create([
            'squad_id' => $this->squad->id,
            'week_id' => $this->week->id
        ]);
        $this->hero = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);
        $this->playerSpirit = factory(PlayerSpirit::class)->state('with-stats')->create([
            'week_id' => $this->week->id
        ]);

        $this->hero->player_spirit_id = $this->playerSpirit->id;
        $this->hero->save();
        $this->domainAction = app(BuildHeroSnapshotAction::class);
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_the_hero_does_not_belong_to_the_squad_of_the_squad_snapshot()
    {
        $squadSnapshot = factory(SquadSnapshot::class)->create([
            'week_id' => $this->week->id
        ]);

        try {
            $this->domainAction->execute($squadSnapshot, $this->hero->fresh());
        } catch (BuildHeroSnapshotException $exception) {
            $this->assertEquals(BuildHeroSnapshotException::CODE_INVALID_HERO, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_the_squad_snapshot_does_not_belong_to_the_current_week()
    {
        $squadSnapshot = factory(SquadSnapshot::class)->create([
            'squad_id' => $this->squad->id
        ]);

        try {
            $this->domainAction->execute($squadSnapshot, $this->hero->fresh());
        } catch (BuildHeroSnapshotException $exception) {
            $this->assertEquals(BuildHeroSnapshotException::CODE_INVALID_SQUAD_SNAPSHOT, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_there_is_no_player_spirit()
    {
        $this->hero->player_spirit_id = null;
        $this->hero->save();

        try {
            $this->domainAction->execute($this->squadSnapshot, $this->hero->fresh());
        } catch (BuildHeroSnapshotException $exception) {
            $this->assertEquals(BuildHeroSnapshotException::CODE_INVALID_PLAYER_SPIRIT, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_the_current_week_doesnt_match_the_player_spirit()
    {
        $diffWeek = factory(Week::class)->create();
        $this->playerSpirit->week_id = $diffWeek->id;
        $this->playerSpirit->save();

        try {
            $this->domainAction->execute($this->squadSnapshot, $this->hero->fresh());
        } catch (BuildHeroSnapshotException $exception) {
            $this->assertEquals(BuildHeroSnapshotException::CODE_INVALID_PLAYER_SPIRIT, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_create_a_hero_snapshot_for_the_hero_and_squad_snapshot()
    {
        $heroSnapshot = $this->domainAction->execute($this->squadSnapshot, $this->hero->fresh());
        $squadSnapshot = $heroSnapshot->squadSnapshot;
        $this->assertEquals($this->squadSnapshot->id, $squadSnapshot->id);
        $hero = $heroSnapshot->hero;
        $this->assertEquals($this->hero->id, $hero->id);
    }
}
