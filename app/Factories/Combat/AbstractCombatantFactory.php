<?php


namespace App\Factories\Combat;


use App\Domain\Models\CombatPosition;

class AbstractCombatantFactory
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
