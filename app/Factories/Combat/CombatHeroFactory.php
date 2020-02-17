<?php


namespace App\Factories\Combat;


use App\Domain\Combat\CombatHero;
use App\Domain\Models\CombatPosition;

class CombatHeroFactory
{
    /** @var int|null */
    protected $heroID;

    /** @var string|null */
    protected $combatPositionName;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        $combatPosition = $this->getCombatPosition();

        return new CombatHero(
            $this->buildHeroID(),
            800,
            800,
            800,
            250,
            20,
            $combatPosition,
            collect()
        );
    }

    public function withHeroID(int $heroID)
    {
        $clone = clone $this;
        $clone->heroID = $heroID;
        return $clone;
    }

    protected function buildHeroID()
    {
        return $this->heroID ?: rand(1, 999999);
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
