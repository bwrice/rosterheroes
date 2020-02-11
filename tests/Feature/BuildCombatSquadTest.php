<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatSquad;
use App\Domain\Combat\CombatHero;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\BuildCombatSquadException;
use App\Facades\HeroService;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BuildCombatSquadTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Week */
    protected $currentWeek;

    /** @var HeroFactory */
    protected $heroFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
        $this->squad = SquadFactory::new()->create();
        $player = PlayerFactory::new()->withPosition()->create();
        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($player)->withStats();
        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);
        $this->heroFactory = HeroFactory::new()
            ->forSquad($this->squad)
            ->withMeasurables()
            ->withItems()
            ->withPlayerSpirit($playerSpiritFactory);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_are_no_combat_ready_heroes()
    {
        $hero = HeroFactory::new()->forSquad($this->squad)->create();
        HeroService::partialMock()->shouldReceive('combatReady')->andReturn(false);
        /** @var BuildCombatSquad $domainAction */
        $domainAction = app(BuildCombatSquad::class);
        try {
            $domainAction->execute($this->squad);
        } catch (BuildCombatSquadException $exception) {
            $this->assertEquals(BuildCombatSquadException::CODE_NO_COMBAT_READY_HEROES, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_create_a_combat_squad()
    {
        $heroCount = 0;
        $heroes = collect();
        foreach([
            HeroRace::HUMAN => HeroClass::RANGER,
            HeroRace::ELF => HeroClass::SORCERER,
            HeroRace::DWARF => HeroClass::WARRIOR,
            HeroRace::ORC => HeroClass::WARRIOR,
                ] as $heroRaceName =>  $heroClassName) {
            $heroCount++;
            $heroes->push($this->heroFactory->heroRace($heroRaceName)->heroClass($heroClassName)->create());
        }
        /** @var BuildCombatSquad $domainAction */
        $domainAction = app(BuildCombatSquad::class);
        $combatSquad = $domainAction->execute($this->squad->fresh());
        $this->assertEquals($this->squad->id, $combatSquad->getSquadID());
        $combatHeroes = $combatSquad->getCombatHeroes();
        $this->assertEquals($heroCount, $combatHeroes->count());
        $combatHeroes->each(function (CombatHero $combatHero) use ($heroes) {
            $match = $heroes->first(function (Hero $hero) use ($combatHero) {
                return $hero->id === $combatHero->getHeroID();
            });
            $this->assertNotNull($match);
            $this->assertGreaterThan(0, $combatHero->getCombatAttacks()->count());
        });
    }
}
