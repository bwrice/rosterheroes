<?php


namespace App\Domain\Combat\Events;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatantInterface;
use Illuminate\Support\Collection;

class CombatantAttacks implements CombatEvent
{
    public const EVENT_STREAM = 'combatant-attacks-targets';

    protected CombatantInterface $combatant;
    protected CombatAttackInterface $combatAttack;
    protected Collection $targets;
    protected int $moment;

    public function __construct(CombatantInterface $combatant, CombatAttackInterface $combatAttack, Collection $targets, int $moment)
    {
        $this->combatant = $combatant;
        $this->combatAttack = $combatAttack;
        $this->targets = $targets;
        $this->moment = $moment;
    }

    public function moment(): int
    {
        return $this->moment;
    }

    public function eventStream(): string
    {
        return self::EVENT_STREAM;
    }

    /**
     * @return CombatantInterface
     */
    public function getCombatant(): CombatantInterface
    {
        return $this->combatant;
    }

    /**
     * @return CombatAttackInterface
     */
    public function getCombatAttack(): CombatAttackInterface
    {
        return $this->combatAttack;
    }

    /**
     * @return Collection
     */
    public function getTargets(): Collection
    {
        return $this->targets;
    }
}
