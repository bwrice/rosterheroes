<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Models\CombatPosition;

class CombatHero
{
    /**
     * @var int
     */
    protected $health;
    /**
     * @var int
     */
    protected $stamina;
    /**
     * @var int
     */
    protected $mana;
    /**
     * @var int
     */
    protected $protection;
    /**
     * @var CombatPosition
     */
    protected $combatPosition;
    /**
     * @var CombatAttackCollection
     */
    protected $combatAttacks;

    public function __construct(
        int $health,
        int $stamina,
        int $mana,
        int $protection,
        CombatPosition $combatPosition,
        CombatAttackCollection $combatAttacks)
    {
        $this->health = $health;
        $this->stamina = $stamina;
        $this->mana = $mana;
        $this->protection = $protection;
        $this->combatPosition = $combatPosition;
        $this->combatAttacks = $combatAttacks;
    }
}
