<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Collection;

class CombatMinion implements Combatant
{
    /**
     * @var int
     */
    protected $minionID;
    /**
     * @var int
     */
    protected $initialHealth;
    /**
     * @var int
     */
    protected $currentHealth;
    /**
     * @var int
     */
    protected $protection;
    /**
     * @var int
     */
    protected $blockChancePercent;
    /**
     * @var CombatPosition
     */
    protected $combatPosition;
    /**
     * @var Collection
     */
    protected $combatAttacks;

    public function __construct(
        int $minionID,
        int $initialHealth,
        int $protection,
        int $blockChancePercent,
        CombatPosition $combatPosition,
        Collection $combatAttacks)
    {
        $this->minionID = $minionID;
        $this->currentHealth = $this->initialHealth = $initialHealth;
        $this->protection = $protection;
        $this->blockChancePercent = $blockChancePercent;
        $this->combatPosition = $combatPosition;
        $this->combatAttacks = $combatAttacks;
    }

    /**
     * @param int $initialDamage
     * @return int
     */
    public function calculateDamageToReceive(int $initialDamage): int
    {
        // TODO: Implement calculateDamageToReceive() method.
    }

    /**
     * @param int $damage
     * @return mixed
     */
    public function receiveDamage(int $damage)
    {
        // TODO: Implement receiveDamage() method.
    }

    /**
     * @param CombatAttackInterface $combatAttack
     * @return mixed
     */
    public function attackBlocked(CombatAttackInterface $combatAttack)
    {
        // TODO: Implement attackBlocked() method.
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
        // TODO: Implement getThreatLevel() method.
    }

    /**
     * @param CombatPosition $combatPositionToCompare
     * @return bool
     */
    public function hasCombatPosition(CombatPosition $combatPositionToCompare): bool
    {
        // TODO: Implement hasCombatPosition() method.
    }

    /**
     * @return int
     */
    public function getMinionID(): int
    {
        return $this->minionID;
    }

    /**
     * @return Collection
     */
    public function getCombatAttacks(): Collection
    {
        return $this->combatAttacks;
    }
}
