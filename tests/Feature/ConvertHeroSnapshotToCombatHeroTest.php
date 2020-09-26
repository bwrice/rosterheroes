<?php

namespace Tests\Feature;

use App\Domain\Actions\ConvertHeroSnapshotToCombatHero;
use App\Domain\Models\MeasurableType;
use App\Factories\Models\HeroSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertHeroSnapshotToCombatHeroTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ConvertHeroSnapshotToCombatHero
     */
    protected function getDomainAction()
    {
        return app(ConvertHeroSnapshotToCombatHero::class);
    }

    /**
     * @test
     */
    public function it_will_convert_a_hero_snapshot_into_a_combat_hero_with_matching_properties()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->withMeasurableSnapshots()->create();
        $combatHero = $this->getDomainAction()->execute($heroSnapshot);

        $this->assertEquals($combatHero->getSourceUuid(), $heroSnapshot->uuid);
        $this->assertEquals($combatHero->getProtection(), $heroSnapshot->protection);
        $this->assertEquals($combatHero->getInitialCombatPositionID(), $heroSnapshot->combat_position_id);
        $this->assertTrue(abs($combatHero->getBlockChancePercent() - $heroSnapshot->block_chance) < 0.01);
    }

    /**
     * @test
     */
    public function it_will_create_a_combat_hero_with_matching_resource_amounts_for_the_hero_snapshot()
    {
        $heroSnapshot = HeroSnapshotFactory::new()->withMeasurableSnapshots()->create();
        $combatHero = $this->getDomainAction()->execute($heroSnapshot);

        $this->assertEquals($combatHero->getCurrentHealth(), $heroSnapshot->getMeasurableSnapshot(MeasurableType::HEALTH)->final_amount);
        $this->assertEquals($combatHero->getCurrentStamina(), $heroSnapshot->getMeasurableSnapshot(MeasurableType::STAMINA)->final_amount);
        $this->assertEquals($combatHero->getCurrentMana(), $heroSnapshot->getMeasurableSnapshot(MeasurableType::MANA)->final_amount);
    }
}
