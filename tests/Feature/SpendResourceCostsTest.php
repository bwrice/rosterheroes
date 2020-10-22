<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\SpendResourceCosts;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\MeasurableType;
use App\Factories\Combat\CombatantFactory;
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
    public function it_will_correctly_update_fixed_costs_for_a_combatant()
    {
        // Stamina
        $stamina = rand(100, 500);
        $combatant = CombatantFactory::new()->withInitialStamina($stamina)->create();
        $cost = rand(10, 50);
        $resourceCost = new FixedResourceCost(MeasurableType::STAMINA, $cost);

        /** @var SpendResourceCosts $domainAction */
        $domainAction = app(SpendResourceCosts::class);
        $result = $domainAction->execute($combatant, $resourceCost);
        $this->assertEquals($cost, $result['stamina_cost']);
        $this->assertEquals($stamina - $cost, $combatant->getCurrentStamina());


        // Mana
        $mana = rand(100, 500);
        $combatant = CombatantFactory::new()->withInitialMana($mana)->create();
        $cost = rand(10, 50);
        $resourceCost = new FixedResourceCost(MeasurableType::MANA, $cost);

        /** @var SpendResourceCosts $domainAction */
        $domainAction = app(SpendResourceCosts::class);
        $result = $domainAction->execute($combatant, $resourceCost);
        $this->assertEquals($cost, $result['mana_cost']);
        $this->assertEquals($mana - $cost, $combatant->getCurrentMana());
    }

    /**
     * @test
     */
    public function it_will_update_percent_costs_for_a_combatant()
    {
        $stamina = 200;
        $combatant = CombatantFactory::new()->withInitialStamina($stamina)->create();
        $percent = 25;
        $resourceCost = new PercentResourceCost(MeasurableType::STAMINA, $percent);

        /** @var SpendResourceCosts $domainAction */
        $domainAction = app(SpendResourceCosts::class);
        $result = $domainAction->execute($combatant, $resourceCost);
        $this->assertEquals(50, $result['stamina_cost']);
        $this->assertEquals(150, $combatant->getCurrentStamina());


        // Mana
        $mana = 200;
        $combatant = CombatantFactory::new()->withInitialMana($mana)->create();
        $percent = 25;
        $resourceCost = new PercentResourceCost(MeasurableType::MANA, $percent);

        /** @var SpendResourceCosts $domainAction */
        $domainAction = app(SpendResourceCosts::class);
        $result = $domainAction->execute($combatant, $resourceCost);
        $this->assertEquals(50, $result['mana_cost']);
        $this->assertEquals(150, $combatant->getCurrentMana());
    }
}
