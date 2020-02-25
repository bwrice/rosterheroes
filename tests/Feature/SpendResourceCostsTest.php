<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\SpendResourceCosts;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use App\Factories\Combat\CombatHeroFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpendResourceCostsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_correctly_update_fixed_costs_for_a_combat_hero()
    {
        // Stamina
        $stamina = rand(100, 500);
        $combatHero = CombatHeroFactory::new()->withStamina($stamina)->create();
        $cost = rand(10, 50);
        $resourceCost = new FixedResourceCost(MeasurableType::STAMINA, $cost);

        /** @var SpendResourceCosts $domainAction */
        $domainAction = app(SpendResourceCosts::class);
        $result = $domainAction->execute($combatHero, $resourceCost);
        $this->assertEquals($cost, $result['stamina_cost']);
        $this->assertEquals($stamina - $cost, $combatHero->getCurrentStamina());


        // Mana
        $mana = rand(100, 500);
        $combatHero = CombatHeroFactory::new()->withMana($mana)->create();
        $cost = rand(10, 50);
        $resourceCost = new FixedResourceCost(MeasurableType::MANA, $cost);

        /** @var SpendResourceCosts $domainAction */
        $domainAction = app(SpendResourceCosts::class);
        $result = $domainAction->execute($combatHero, $resourceCost);
        $this->assertEquals($cost, $result['mana_cost']);
        $this->assertEquals($mana - $cost, $combatHero->getCurrentMana());
    }
}
