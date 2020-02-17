<?php


namespace App\Factories\Combat;


use App\Domain\Combat\CombatHero;
use App\Domain\Models\CombatPosition;

class CombatHeroFactory
{
    /** @var int|null */
    protected $heroID;

    /** @var CombatPosition */
    protected $combatPosition;

    public static function new()
    {
        return new self();
    }

    public function create()
    {
        /** @var CombatPosition $combatPosition */
        $combatPosition = $this->combatPosition ?: CombatPosition::query()->inRandomOrder()->first();

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

    protected function withCombatPosition(CombatPosition $combatPosition)
    {
        $clone = clone $this;
        $clone->combatPosition = $combatPosition;
        return $clone;
    }

}
