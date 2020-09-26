<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractCombatant implements Combatant, Arrayable
{
    protected int $initialHealth, $currentHealth, $protection, $initialCombatPositionID;
    protected float $blockChancePercent;
    protected CombatAttackCollection $combatAttacks;
    protected array $inheritedCombatPositionIDs = [];

    public function __construct(
        int $health,
        int $protection,
        float $blockChancePercent,
        int $combatPositionID,
        CombatAttackCollection $combatAttacks)
    {
        $this->initialHealth = $this->currentHealth = $health;
        $this->protection = $protection;
        $this->blockChancePercent = $blockChancePercent;
        $this->initialCombatPositionID = $combatPositionID;
        $this->combatAttacks = $combatAttacks;
    }

    public function calculateDamageToReceive(int $initialDamage): int
    {
        $multiplier = 500 / (500 + $this->protection);
        return (int) min(ceil($multiplier * $initialDamage), $this->currentHealth);
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
     * @param CombatAttackInterface $combatAttack
     * @return bool|mixed
     */
    public function attackBlocked(CombatAttackInterface $combatAttack)
    {
        $rand = rand(1, 100);
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
     * @param int $combatPositionID
     * @return bool
     */
    public function hasCombatPosition(int $combatPositionID): bool
    {
        return in_array($combatPositionID, $this->allCombatPositions());
    }

    /**
     * @return array
     */
    public function allCombatPositions()
    {
        $combatPositions = $this->inheritedCombatPositionIDs;
        $combatPositions[] = $this->initialCombatPositionID;
        return $combatPositions;
    }

    /**
     * @return CombatAttackCollection
     */
    public function getCombatAttacks(): CombatAttackCollection
    {
        return $this->combatAttacks;
    }

    /**
     * @return int
     */
    public function getInitialCombatPositionID()
    {
        return $this->initialCombatPositionID;
    }

    /**
     * @param array $inheritedCombatPositionIDs
     * @return $this
     */
    public function setInheritedCombatPositions(array $inheritedCombatPositionIDs)
    {
        $this->inheritedCombatPositionIDs = $inheritedCombatPositionIDs;
        return $this;
    }

    /**
     * @return float
     */
    public function getThreatLevel(): float
    {
        return $this->getDPS();
    }

    abstract protected function getDPS();

    public function toArray()
    {
        return [
            'initialHealth' => $this->initialHealth,
            'currentHealth' => $this->currentHealth,
            'protection' => $this->protection,
            'blockChancePercent' => $this->blockChancePercent,
            'combatAttacks' => $this->combatAttacks->toArray(),
            'initialCombatPositionID' => $this->initialCombatPositionID,
            'inheritedCombatPositionIDs' => $this->inheritedCombatPositionIDs
        ];
    }

    /**
     * @param int $currentHealth
     * @return $this
     */
    public function setCurrentHealth(int $currentHealth)
    {
        $this->currentHealth = $currentHealth;
        return $this;
    }

    abstract public function getReadyAttacks(): CombatAttackCollection;

    /**
     * @return int
     */
    public function getProtection(): int
    {
        return $this->protection;
    }

    /**
     * @return float
     */
    public function getBlockChancePercent(): float
    {
        return $this->blockChancePercent;
    }
}
