<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatHero;
use App\Domain\Actions\Combat\BuildCombatSquad;
use App\Domain\Combat\CombatHero;
use App\Domain\Combat\CombatSquad;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\BuildCombatSquadException;
use App\Facades\HeroService;
use App\Factories\Combat\CombatHeroFactory;
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
        $this->heroFactory = HeroFactory::new()->forSquad($this->squad);
//        $player = PlayerFactory::new()->withPosition()->create();
//        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($player)->withStats();
//        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);
//        $this->heroFactory = HeroFactory::new()
//            ->forSquad($this->squad)
//            ->withMeasurables()
//            ->withItems()
//            ->withPlayerSpirit($playerSpiritFactory);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_are_no_combat_ready_heroes()
    {
        $hero = $this->heroFactory->create();
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
        $hero1 = $this->heroFactory->create();
        $hero2 = $this->heroFactory->create();
        HeroService::partialMock()->shouldReceive('combatReady')->andReturn(true);

        $combatHeroFactory = CombatHeroFactory::new();
        $buildCombatHeroMock = \Mockery::mock(BuildCombatHero::class)
            ->shouldReceive('execute')
            ->andReturn($combatHeroFactory->create())
            ->getMock();

        app()->instance(BuildCombatHero::class, $buildCombatHeroMock);

        /** @var BuildCombatSquad $domainAction */
        $domainAction = app(BuildCombatSquad::class);
        $combatSquad = $domainAction->execute($this->squad->fresh());
        $this->assertTrue($combatSquad instanceof CombatSquad);
        $this->assertEquals($this->squad->id, $combatSquad->getSquadID());
        $combatHeroes = $combatSquad->getCombatHeroes();
        $this->assertEquals(2, $combatHeroes->count());
    }
}
