<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class Combatant implements CombatantInterface, Arrayable
{
    protected string $sourceUuid, $combatantUuid;
    protected int $initialHealth, $currentHealth, $initialStamina, $currentStamina, $initialMana, $currentMana,
        $protection, $combatPositionID;
    protected float $blockChancePercent;
    protected CombatAttackCollection $combatAttacks;
    protected array $inheritedCombatPositionIDs = [];

    public function __construct(
        string $sourceUuid,
        int $initialHealth,
        int $initialStamina,
        int $initialMana,
        int $protection,
        float $blockChancePercent,
        int $combatPositionID,
        CombatAttackCollection $combatAttacks)
    {
        $this->sourceUuid = $sourceUuid;
        $this->combatantUuid = (string) Str::uuid();
        $this->initialHealth = $this->currentHealth = $initialHealth;
        $this->initialStamina = $this->currentStamina = $initialStamina;
        $this->initialMana = $this->currentMana = $initialMana;
        $this->protection = $protection;
        $this->blockChancePercent = $blockChancePercent;
        $this->combatPositionID = $combatPositionID;
        $this->combatAttacks = $combatAttacks;
    }

    public function calculateDamageToReceive(int $initialDamage): int
    {
        $multiplier = 500 / (500 + $this->protection);
        return (int) min(ceil($multiplier * $initialDamage), $this->currentHealth);
    }

    /**
     * @param int $amount
     * @return mixed|void
     */
    public function updateCurrentHealth(int $amount)
    {
        $this->currentHealth = $amount;
        return $this;
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
        $combatPositions[] = $this->combatPositionID;
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
    public function getCombatPositionID()
    {
        return $this->combatPositionID;
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

    protected function getDPS()
    {
        // TODO;
        return 1;
    }

    public function toArray()
    {
        return [
            'initialHealth' => $this->initialHealth,
            'currentHealth' => $this->currentHealth,
            'protection' => $this->protection,
            'blockChancePercent' => $this->blockChancePercent,
            'combatAttacks' => $this->combatAttacks->toArray(),
            'initialCombatPositionID' => $this->combatPositionID,
            'inheritedCombatPositionIDs' => $this->inheritedCombatPositionIDs
        ];
    }

    public function getInitialHealth(): int
    {
        return $this->initialHealth;
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function setCurrentHealth(int $currentHealth)
    {
        $this->currentHealth = $currentHealth;
        return $this;
    }

    public function getInitialStamina(): int
    {
        return $this->initialStamina;
    }

    public function getCurrentStamina(): int
    {
        return $this->currentStamina;
    }

    public function getInitialMana(): int
    {
        return $this->initialMana;
    }

    public function getCurrentMana(): int
    {
        return $this->currentMana;
    }

    public function setCurrentStamina(int $amount)
    {
        $this->currentStamina = $amount;
        return $this;
    }

    public function setCurrentMana(int $amount)
    {
        $this->currentMana = $amount;
        return $this;
    }

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

    public function getReadyAttacks(): CombatAttackCollection
    {
        // TODO: Implement getReadyAttacks() method.
    }

    /**
     * @return string
     */
    public function getSourceUuid(): string
    {
        return $this->sourceUuid;
    }

    /**
     * @return string
     */
    public function getCombatantUuid(): string
    {
        return $this->combatantUuid;
    }
}
