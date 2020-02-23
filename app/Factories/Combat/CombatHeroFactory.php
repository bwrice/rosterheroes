<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;

class CombatHeroFactory extends AbstractCombatantFactory
{
    /** @var int|null */
    protected $heroID;

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
}
