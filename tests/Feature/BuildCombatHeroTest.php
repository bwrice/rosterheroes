<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatHero;
use App\Domain\Actions\Combat\BuildHeroCombatAttack;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Models\Item;
use App\Factories\Combat\HeroCombatAttackFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildCombatHeroTest extends TestCase
{
    use DatabaseTransactions;

    /** @var HeroFactory */
    protected $heroFactory;

    public function setUp(): void
    {
        parent::setUp();
        $player = PlayerFactory::new()->withPosition()->create();
        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($player)->withStats();
        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);
        $this->heroFactory = HeroFactory::new()
            ->withMeasurables()
            ->withItems()
            ->withPlayerSpirit($playerSpiritFactory);
    }

    /**
     * @test
     */
    public function it_will_create_a_valid_combat_hero()
    {
        $mockBuildCombatAttack = \Mockery::mock(BuildHeroCombatAttack::class)
            ->shouldReceive('execute')
            ->andReturn(HeroCombatAttackFactory::new()->create())
            ->getMock();

        app()->instance(BuildHeroCombatAttack::class, $mockBuildCombatAttack);
        /** @var BuildCombatHero $domainAction */
        $domainAction = app(BuildCombatHero::class);

        $hero = $this->heroFactory->create();
        $combatHero = $domainAction->execute($hero);
        $this->assertTrue($combatHero instanceof Combatant);

        $attacksCount = $hero->items->sum(function (Item $item) {
            return $item->getAttacks()->count();
        });

        $this->assertEquals($attacksCount, $combatHero->getCombatAttacks()->count());
    }
}
