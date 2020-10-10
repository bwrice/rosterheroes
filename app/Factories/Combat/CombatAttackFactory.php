<?php


namespace App\Factories\Combat;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Attacks\CombatAttack;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\DamageType;
use App\Domain\Models\TargetPriority;
use App\Factories\Models\AttackFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CombatAttackFactory
{
    protected ?string $attackerPositionName = null, $targetPositionName = null, $targetPriorityName = null, $damageTypeName = null;
    protected ?int $damage = null, $grade = null, $maxTargetsCount = null;
    protected ?float $combatSpeed = null;
    protected ?Collection $resourceCostsCollection = null;

    public static function new()
    {
        return new static();
    }

    public function create()
    {
        return new CombatAttack(
            'Test Combat Attack',
            (string) Str::uuid(),
            (string) Str::uuid(),
            $this->getDamage(),
            $this->getCombatSpeed(),
            $this->getGrade(),
            $this->getMaxTargetsCount(),
            $this->getAttackerPosition()->id,
            $this->getTargetPosition()->id,
            $this->getTargetPriority()->id,
            $this->getDamageType()->id,
            $this->resourceCostsCollection ?: new ResourceCostsCollection()
        );
    }

    protected function getDamage()
    {
        return $this->damage ?: 100;
    }

    protected function getCombatSpeed()
    {
        return $this->combatSpeed ?: 10;
    }

    protected function getGrade()
    {
        return $this->grade ?: 10;
    }

    protected function getMaxTargetsCount()
    {
        return $this->maxTargetsCount ?: rand(1, 8);
    }

    protected function getAttackUuid()
    {
        return AttackFactory::new()->create()->uuid;
    }

    /**
     * @param string $combatPositionName
     * @return static
     */
    public function withAttackerPosition(string $combatPositionName)
    {
        $clone = clone $this;
        $clone->attackerPositionName = $combatPositionName;
        return $clone;
    }

    /**
     * @param string $combatPositionName
     * @return static
     */
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
        $clone->maxTargetsCount;
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
        if ($this->targetPriorityName) {
            return TargetPriority::query()->where('name', '=', $this->targetPriorityName)->first();
        }
        return TargetPriority::query()->inRandomOrder()->first();
    }

    protected function getDamageType()
    {
        if ($this->damageTypeName) {
            return DamageType::query()->where('name', '=', $this->damageTypeName)->first();
        }
        return DamageType::query()->inRandomOrder()->first();
    }
}