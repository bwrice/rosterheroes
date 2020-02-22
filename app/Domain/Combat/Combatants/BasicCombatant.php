<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Models\CombatPosition;
use Illuminate\Support\Collection;

class BasicCombatant implements Combatant
{
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
     * @var float
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
    /**
     * @var CombatPosition
     */
    protected $initialCombatPosition;
    /**
     * @var Collection
     */
    protected $inheritedCombatPositions;

    public function __construct(
        int $health,
        int $protection,
        float $blockChancePercent,
        CombatPosition $combatPosition,
        Collection $combatAttacks)
    {
        $this->initialHealth = $this->currentHealth = $health;
        $this->protection = $protection;
        $this->blockChancePercent = $blockChancePercent;
        $this->initialCombatPosition = $combatPosition;
        $this->inheritedCombatPositions = new CombatPositionCollection();
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
     * @param CombatAttackInterface $combatAttack
     * @return bool|mixed
     * @throws \Exception
     */
    public function attackBlocked(CombatAttackInterface $combatAttack)
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

    /**
     * @param CombatPosition $combatPositionToCompare
     * @return bool
     */
    public function hasCombatPosition(CombatPosition $combatPositionToCompare): bool
    {
        $match = $this->allCombatPositions()->first(function (CombatPosition $combatPosition) use ($combatPositionToCompare) {
            return $combatPosition->id === $combatPositionToCompare->id;
        });
        return ! is_null($match);
    }

    public function allCombatPositions()
    {
        return clone($this->inheritedCombatPositions)->push($this->initialCombatPosition)->unique();
    }
}
