<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use App\Factories\Models\AttackFactory;
use Illuminate\Support\Str;

class CombatAttackFactory
{
    protected $attackerPositionName;

    protected $targetPositionName;

    protected $targetPriorityName;

    protected $damageTypeName;

    protected $damage;

    protected $combatSpeed;

    protected $grade;

    protected $maxTargetCount;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $name = 'Test_Hero_Combat_Attack ' . rand(1, 99999);
        return new CombatAttack(
            $name,
            AttackFactory::new()->create()->uuid,
            $this->damage ?: 100,
            $this->combatSpeed ?: 10,
            $this->grade ?: 10,
            $this->getAttackerPosition(),
            $this->getTargetPosition(),
            $this->getTargetPriority(),
            $this->getDamageType(),
            $this->maxTargetCount ?: rand(1, 8)
        );
    }

    public function withAttackerPosition(string $combatPositionName)
    {
        $clone = clone $this;
        $clone->attackerPositionName = $combatPositionName;
        return $clone;
    }

    public function withTargetPosition(string $combatPositionName)
    {
        $clone = clone $this;
        $clone->targetPositionName = $combatPositionName;
        return $clone;
    }

    public function withTargetPriority(string $targetPriorityName)
    {
        $clone = clone $this;
        $clone->targetPriorityName = $targetPriorityName;
        return $clone;
    }

    public function withDamageType(string $damageTypeName)
    {
        $clone = clone $this;
        $clone->damageTypeName = $damageTypeName;
        return $clone;
    }

    public function withDamage(int $damage)
    {
        $clone = clone $this;
        $clone->damage = $damage;
        return $clone;
    }

    public function withCombatSpeed(int $combatSpeed)
    {
        $clone = clone $this;
        $clone->combatSpeed = $combatSpeed;
        return $clone;
    }

    public function withGrade(int $grade)
    {
        $clone = clone $this;
        $clone->grade = $grade;
        return $clone;
    }

    public function withMaxTargetCount(int $maxTargetCount)
    {
        $clone = clone $this;
        $clone->maxTargetCount;
        return $clone;
    }

    protected function getAttackerPosition()
    {
        if ($this->attackerPositionName) {
            return CombatPosition::query()->where('name', '=', $this->attackerPositionName)->first();
        }
        return CombatPosition::query()->inRandomOrder()->first();
    }

    protected function getTargetPosition()
    {
        if ($this->targetPositionName) {
            return CombatPosition::query()->where('name', '=', $this->targetPositionName)->first();
        }
        return CombatPosition::query()->inRandomOrder()->first();
    }

    protected function getTargetPriority()
    {
        if ($this->targetPositionName) {
            return TargetPriority::query()->where('name', '=', $this->targetPriorityName)->first();
        }
        return TargetPriority::query()->inRandomOrder()->first();
    }

    protected function getDamageType()
    {
        if ($this->targetPositionName) {
            return DamageType::query()->where('name', '=', $this->damageTypeName)->first();
        }
        return DamageType::query()->inRandomOrder()->first();
    }
}
