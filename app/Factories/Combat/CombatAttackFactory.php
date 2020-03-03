<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use Illuminate\Support\Str;

class CombatAttackFactory
{
    protected $attackerPositionName;

    protected $targetPositionName;

    protected $targetPriorityName;

    protected $damageTypeName;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $name = 'Test_Hero_Combat_Attack ' . rand(1, 99999);
        $maxTargetCount = rand(3, 8);
        return new CombatAttack(
            $name,
            (string) Str::uuid(),
            100,
            10,
            10,
            $this->getAttackerPosition(),
            $this->getTargetPosition(),
            $this->getTargetPriority(),
            $this->getDamageType(),
            $maxTargetCount
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
