<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Models\CombatPosition;

class CombatHero
{
    /**
     * @var int
     */
    protected $initialHealth;
    /**
     * @var int
     */
    protected $initialStamina;
    /**
     * @var int
     */
    protected $initialMana;
    /**
     * @var int
     */
    protected $currentHealth;
    /**
     * @var int
     */
    protected $currentStamina;
    /**
     * @var int
     */
    protected $currentMana;
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
        $this->initialHealth = $this->currentHealth = $health;
        $this->initialStamina = $this->currentStamina = $stamina;
        $this->initialMana = $this->currentMana = $mana;
        $this->protection = $protection;
        $this->combatPosition = $combatPosition;
        $this->combatAttacks = $combatAttacks;
    }
}
