<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatHero;
use App\Domain\Actions\Combat\BuildCombatSquad;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\CombatGroups\CombatSquad;
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
        $this->assertEquals($this->squad->uuid, $combatSquad->getSquadUuid());
        $combatHeroes = $combatSquad->getCombatHeroes();
        $this->assertEquals(2, $combatHeroes->count());
    }
}
