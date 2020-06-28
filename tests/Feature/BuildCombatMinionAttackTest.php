<?php

namespace Tests\Feature;

use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Actions\Combat\BuildMinionCombatAttack;
use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Attacks\MinionCombatAttack;
use App\Domain\Models\Attack;
use App\Domain\Models\Minion;
use App\Factories\Models\AttackFactory;
use App\Factories\Models\MinionFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class BuildCombatMinionAttackTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Minion */
    protected $minion;

    /** @var Attack */
    protected $attack;

    public function setUp(): void
    {
        parent::setUp();

        $this->minion = MinionFactory::new()->create();
        $this->attack = AttackFactory::new()->create();
    }

    /**
     * @test
     */
    public function it_will_build_a_minion_combat_attack()
    {
        /** @var BuildMinionCombatAttack $domainAction */
        $domainAction = app(BuildMinionCombatAttack::class);
        $minionCombatAttack = $domainAction->execute($this->attack, $this->minion, Str::uuid());
        $this->assertTrue($minionCombatAttack instanceof MinionCombatAttack);
        $this->assertTrue($minionCombatAttack instanceof CombatAttackInterface);
    }

    /**
     * @test
     */
    public function it_will_have_the_expected_combat_speed()
    {
        /** @var BuildMinionCombatAttack $domainAction */
        $domainAction = app(BuildMinionCombatAttack::class);
        $minionCombatAttack = $domainAction->execute($this->attack, $this->minion, Str::uuid());

        $this->attack->setHasAttacks($this->minion);
        $expectedSpeed = $this->attack->getCombatSpeed();
        $diff = abs($expectedSpeed - $minionCombatAttack->getCombatSpeed());
        $this->assertLessThan(PHP_FLOAT_EPSILON, $diff);
    }

    /**
     * @test
     */
    public function it_will_have_the_expected_combat_damage()
    {
        /** @var BuildMinionCombatAttack $domainAction */
        $domainAction = app(BuildMinionCombatAttack::class);
        $minionCombatAttack = $domainAction->execute($this->attack, $this->minion, Str::uuid());

        $this->attack->setHasAttacks($this->minion);

        /** @var CalculateCombatDamage $calculateDamage */
        $calculateDamage = app(CalculateCombatDamage::class);
        /** @var CalculateFantasyPower $calculateFantasyPower */
        $calculateFantasyPower = app(CalculateFantasyPower::class);
        $fantasyPower = $calculateFantasyPower->execute($this->minion->getFantasyPoints());
        $expectedDamage = $calculateDamage->execute($this->attack, $fantasyPower);

        $this->assertEquals($expectedDamage, $minionCombatAttack->getDamage());
    }
}
