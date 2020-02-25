<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;

class CombatHeroFactory extends AbstractCombatantFactory
{
    /** @var int|null */
    protected $heroID;

    protected $health;

    protected $stamina;

    protected $mana;

    public function create()
    {
        $combatPosition = $this->getCombatPosition();

        return new CombatHero(
            $this->buildHeroID(),
            is_null($this->health) ? 800 : $this->health,
            is_null($this->stamina) ? 500 : $this->stamina,
            is_null($this->mana) ? 400 : $this->mana,
            100,
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

    public function withMana(int $mana)
    {
        $clone = clone $this;
        $clone->mana = $mana;
        return $clone;
    }

    public function withStamina(int $stamina)
    {
        $clone = clone $this;
        $clone->stamina = $stamina;
        return $clone;
    }

    public function withHealth(int $health)
    {
        $clone = clone $this;
        $clone->health = $health;
        return $clone;
    }
}
