<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Combatants\AbstractCombatant;
use App\Domain\Models\CombatPosition;

abstract class AbstractCombatantFactory
{
    /** @var string|null */
    protected $combatPositionName;

    /**
     * @return static
     */
    public static function new()
    {
        return new static();
    }

    /**
     * @return AbstractCombatant
     */
    abstract public function create();

    protected function getCombatPosition()
    {
        if ($this->combatPositionName) {
            return CombatPosition::forName($this->combatPositionName);
        }
        /** @var CombatPosition $combatPosition */
        $combatPosition = CombatPosition::query()->inRandomOrder()->first();
        return $combatPosition;
    }

    public function withCombatPosition(string $combatPositionName)
    {
        $clone = clone $this;
        $clone->combatPositionName = $combatPositionName;
        return $clone;
    }

}
