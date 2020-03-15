<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildHeroCombatAttack;
use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildHeroCombatAttackTest extends TestCase
{
    /** @var Hero */
    protected $hero;

    /** @var Item */
    protected $item;

    /** @var Attack */
    protected $attack;

    public function setUp(): void
    {
        parent::setUp();

        $this->hero = HeroFactory::new()->withMeasurables()->create();
        $this->item = ItemFactory::new()->create();
        $this->attack = AttackFactory::new()->create();
    }

    /**
     * @test
     */
    public function it_will_build_a_hero_combat_attack()
    {
        $fantasyPower = rand(1, 50);
        /** @var BuildHeroCombatAttack $domainAction */
        $domainAction = app(BuildHeroCombatAttack::class);
        $heroCombatAttack = $domainAction->execute($this->attack, $this->item, $this->hero, $fantasyPower);
        $this->assertTrue($heroCombatAttack instanceof HeroCombatAttack);
        $this->assertTrue($heroCombatAttack instanceof CombatAttackInterface);
    }

    /**
     * @test
     */
    public function it_will_have_the_expected_combat_damage()
    {
        $fantasyPower = rand(1, 50);
        /** @var BuildHeroCombatAttack $domainAction */
        $domainAction = app(BuildHeroCombatAttack::class);
        $heroCombatAttack = $domainAction->execute($this->attack, $this->item, $this->hero, $fantasyPower);

        $this->item->setUsesItems($this->hero);
        $this->attack->setHasAttacks($this->item);

        /** @var CalculateCombatDamage $calculateDamage */
        $calculateDamage = app(CalculateCombatDamage::class);
        $expectedDamage = $calculateDamage->execute($this->attack, $fantasyPower);

        $this->assertEquals($expectedDamage, $heroCombatAttack->getDamage());
    }

    /**
     * @test
     */
    public function it_will_have_the_expected_combat_speed()
    {
        $fantasyPower = rand(1, 50);
        /** @var BuildHeroCombatAttack $domainAction */
        $domainAction = app(BuildHeroCombatAttack::class);
        $heroCombatAttack = $domainAction->execute($this->attack, $this->item, $this->hero, $fantasyPower);

        $this->item->setUsesItems($this->hero);
        $this->attack->setHasAttacks($this->item);
        $expectedSpeed = $this->attack->getCombatSpeed();
        $diff = abs($expectedSpeed - $heroCombatAttack->getCombatSpeed());
        $this->assertLessThan(PHP_FLOAT_EPSILON, $diff);
    }
}
