<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildHeroCombatAttack;
use App\Domain\Combat\CombatAttackInterface;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildHeroCombatAttackTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_build_a_hero_combat_attack()
    {
        $hero = HeroFactory::new()->create();
        $item = ItemFactory::new()->create();
        $attack = AttackFactory::new()->create();
        $fantasyPower = rand(5, 50);
        /** @var BuildHeroCombatAttack $domainAction */
        $domainAction = app(BuildHeroCombatAttack::class);
        $heroCombatAttack = $domainAction->execute($attack, $item, $hero, $fantasyPower);
        $this->assertTrue($heroCombatAttack instanceof CombatAttackInterface);
    }
}
