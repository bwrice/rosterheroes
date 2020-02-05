<?php


namespace App\Domain\Combat;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Models\CombatPosition;

class CombatHero implements Combatant
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
     * @var float
     */
    protected $blockChancePercent;
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
        float $blockChancePercent,
        CombatPosition $combatPosition,
        CombatAttackCollection $combatAttacks)
    {
        $this->initialHealth = $this->currentHealth = $health;
        $this->initialStamina = $this->currentStamina = $stamina;
        $this->initialMana = $this->currentMana = $mana;
        $this->protection = $protection;
        $this->blockChancePercent = $blockChancePercent;
        $this->combatPosition = $combatPosition;
        $this->combatAttacks = $combatAttacks;
    }

    public function calculateDamageToReceive(int $initialDamage): int
    {
        $multiplier = 500 / (500 + $this->protection);
        return (int) ceil($multiplier * $initialDamage);
    }

    /**
     * @param int $damage
     * @return mixed|void
     */
    public function receiveDamage(int $damage)
    {
        $this->currentHealth = (int) max($this->currentHealth - $damage, 0);
    }

    /**
     * @param CombatAttack $combatAttack
     * @return bool|mixed
     * @throws \Exception
     */
    public function attackBlocked(CombatAttack $combatAttack)
    {
        $rand = random_int(1, 100);
        return $rand <= $this->blockChancePercent;
    }

    /**
     * @return int
     */
    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    /**
     * @return float
     */
    public function getThreatLevel(): float
    {
        return $this->getDPS();
    }

    protected function getDPS()
    {
        // TODO
        return 1;
    }
}
