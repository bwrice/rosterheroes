<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Collections\AbstractCombatAttackCollection;
use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Models\CombatPosition;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

abstract class AbstractCombatant implements Combatant, Arrayable
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
        AbstractCombatAttackCollection $combatAttacks)
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

    /**
     * @return Collection
     */
    public function getCombatAttacks(): Collection
    {
        return $this->combatAttacks;
    }

    /**
     * @return CombatPosition
     */
    public function getInitialCombatPosition(): CombatPosition
    {
        return $this->initialCombatPosition;
    }

    /**
     * @param CombatPositionCollection $inheritedCombatPositions
     * @return static
     */
    public function setInheritedCombatPositions(CombatPositionCollection $inheritedCombatPositions)
    {
        $this->inheritedCombatPositions = $inheritedCombatPositions;
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
            'initialCombatPositionID' => $this->initialCombatPosition->id,
            'inheritedCombatPositionIDs' => $this->inheritedCombatPositions->pluck('id')->toArray()
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
}
