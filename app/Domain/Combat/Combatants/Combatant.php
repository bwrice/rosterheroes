<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\CombatAttackCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Combatant implements CombatantInterface, Arrayable
{
    protected string $sourceUuid, $combatantUuid;
    protected int $initialHealth, $currentHealth, $initialStamina, $currentStamina, $initialMana, $currentMana,
        $protection, $combatPositionID;
    protected float $blockChancePercent;
    protected Collection $combatAttacks;

    public function __construct(
        string $sourceUuid,
        int $initialHealth,
        int $initialStamina,
        int $initialMana,
        int $protection,
        float $blockChancePercent,
        int $combatPositionID,
        Collection $combatAttacks)
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
     * @return Collection
     */
    public function getCombatAttacks(): Collection
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
            'source_uuid' => $this->sourceUuid,
            'combatant_uuid' => $this->combatantUuid,
            'initial_health' => $this->initialHealth,
            'current_health' => $this->currentHealth,
            'initial_stamina' => $this->initialStamina,
            'current_stamina' => $this->currentStamina,
            'initial_mana' => $this->initialMana,
            'current_mana' => $this->currentMana,
            'protection' => $this->protection,
            'block_chance_percent' => $this->blockChancePercent,
            'combat_attacks' => $this->combatAttacks->toArray(),
            'combat_position_id' => $this->combatPositionID
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
