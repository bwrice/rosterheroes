<?php


namespace App\Factories\Combat;


use App\Domain\Collections\CombatAttackCollection;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\Squad;
use App\Factories\Models\HeroFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CombatantFactory
{
    protected ?string $combatPositionName = null, $sourceUuid = null;
    protected ?int $initialHealth = null, $initialStamina = null, $initialMana = null, $protection = null;
    protected ?float $blockChancePercent = null;
    protected ?Collection $combatAttacks = null;
    /**
     * @return static
     */
    public static function new()
    {
        return new static();
    }

    public function create()
    {
        $combatPosition = $this->getCombatPosition();

        return new Combatant(
            $this->sourceUuid ?: (string) Str::uuid(),
            is_null($this->initialHealth) ? 800 : $this->initialHealth,
            is_null($this->initialStamina) ? 500 : $this->initialStamina,
            is_null($this->initialMana) ? 400 : $this->initialMana,
            is_null($this->protection) ? 100 : $this->protection,
            is_null($this->blockChancePercent) ? 10: $this->blockChancePercent,
            $combatPosition->id,
            $this->combatAttacks ?: collect()
        );
    }

    public function withInitialMana(int $initialMana)
    {
        $clone = clone $this;
        $clone->initialMana = $initialMana;
        return $clone;
    }

    public function withInitialStamina(int $initialStamina)
    {
        $clone = clone $this;
        $clone->initialStamina = $initialStamina;
        return $clone;
    }

    public function withInitialHealth(int $initialHealth)
    {
        $clone = clone $this;
        $clone->initialHealth = $initialHealth;
        return $clone;
    }

    public function withProtection(int $protection)
    {
        $clone = clone $this;
        $clone->protection = $protection;
        return $clone;
    }

    public function withBlockChancePercent(float $blockChancePercent)
    {
        $clone = clone $this;
        $clone->blockChancePercent = $blockChancePercent;
        return $clone;
    }

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

    public function withCombatAttacks(Collection $combatAttacks)
    {
        $clone = clone $this;
        $clone->combatAttacks = $combatAttacks;
        return $clone;
    }

}
