<?php


namespace App\Factories\Combat;


use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Squad;
use App\Factories\Models\HeroFactory;
use Illuminate\Support\Collection;

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
    }/** @var string|null */
    protected $heroUuid;

    /** @var HeroFactory */
    protected $heroFactory;

    protected $health;

    protected $stamina;

    protected $mana;

    protected $protection;

    protected $blockChancePercent;

    /** @var Squad|null */
    protected $squad;

    /** @var Collection|null */
    protected $heroCombatAttackFactories;

    public function create()
    {
        $combatPosition = $this->getCombatPosition();
        $heroUuid = $this->getHeroUuid();
        $heroCombatAttacks = $this->getHeroCombatAttacks($heroUuid);

        return new CombatHero(
            $heroUuid,
            is_null($this->health) ? 800 : $this->health,
            is_null($this->stamina) ? 500 : $this->stamina,
            is_null($this->mana) ? 400 : $this->mana,
            is_null($this->protection) ? 100 : $this->protection,
            is_null($this->blockChancePercent) ? 10: $this->blockChancePercent,
            $combatPosition->id,
            $heroCombatAttacks
        );
    }

    /**
     * @return Combatant
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
