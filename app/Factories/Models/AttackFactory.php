<?php


namespace App\Factories\Models;


use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;

class AttackFactory
{
    /** @var string|null */
    protected $attackerPositionName;
    /** @var string|null */
    protected $targetPositionName;
    /** @var string|null */
    protected $damageTypeName;
    /** @var string|null */
    protected $targetPriorityName;

    public static function new()
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        /** @var Attack $attack */
        $attack = Attack::query()->create(array_merge([
            'name' => 'Factory Attack ' . rand(1, 999999),
            'config_path' => '/Yaml/Attacks/test_attack.yaml',
            'attacker_position_id' => $this->getAttackerPosition()->id,
            'target_position_id' => $this->getTargetPosition()->id,
            'target_priority_id' => $this->getTargetPriority()->id,
            'damage_type_id' => $this->getDamageType()->id,
        ], $extra));
        return $attack;
    }

    protected function getAttackerPosition()
    {
        if ($this->attackerPositionName) {
            $combatPosition = CombatPosition::query()->where('name', '=', $this->attackerPositionName)->first();
        } else {
            $combatPosition = CombatPosition::query()->inRandomOrder()->first();
        }
        /** @var CombatPosition $combatPosition */
        return $combatPosition;
    }

    protected function getTargetPosition()
    {
        if ($this->targetPriorityName) {
            $combatPosition = CombatPosition::query()->where('name', '=', $this->targetPositionName)->first();
        } else {
            $combatPosition = CombatPosition::query()->inRandomOrder()->first();
        }
        /** @var CombatPosition $combatPosition */
        return $combatPosition;
    }

    protected function getTargetPriority()
    {
        if ($this->targetPriorityName) {
            $targetPriority = TargetPriority::query()->where('name', '=', $this->targetPriorityName)->first();
        } else {
            $targetPriority = TargetPriority::query()->inRandomOrder()->first();
        }
        /** @var TargetPriority $targetPriority */
        return $targetPriority;
    }

    protected function getDamageType()
    {
        if ($this->damageTypeName) {
            $damageType = CombatPosition::query()->where('name', '=', $this->damageTypeName)->first();
        } else {
            $damageType = CombatPosition::query()->inRandomOrder()->first();
        }
        /** @var DamageType $damageType */
        return $damageType;
    }

    public function withAttackPosition(string $combatPositionName)
    {
        $clone = clone $this;
        $this->attackerPositionName = $combatPositionName;
        return $clone;
    }

    public function withTargetPosition(string $combatPositionName)
    {
        $clone = clone $this;
        $this->targetPositionName = $combatPositionName;
        return $clone;
    }

    public function withTargetPriority(string $targetPriorityName)
    {
        $clone = clone $this;
        $this->targetPriorityName = $targetPriorityName;
        return $clone;
    }

    public function withDamageType(string $damageTypeName)
    {
        $clone = clone $this;
        $this->damageTypeName = $damageTypeName;
        return $clone;
    }

}
