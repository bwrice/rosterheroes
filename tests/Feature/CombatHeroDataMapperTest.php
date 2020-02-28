<?php

namespace Tests\Feature;

use App\Domain\Combat\Combatants\CombatHeroDataMapper;
use App\Factories\Combat\CombatHeroFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CombatHeroDataMapperTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_map_the_data_array_into_a_combat_hero()
    {
        $original = CombatHeroFactory::new()->create();

        /** @var CombatHeroDataMapper $mapper */
        $mapper = app(CombatHeroDataMapper::class);
        $combatHero = $mapper->getCombatHero($original->toArray());
        $this->assertTrue($original == $combatHero);
    }
}
