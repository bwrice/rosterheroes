<?php


namespace App\Domain\Combat\Events;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatantInterface;

class AttackBlocked implements CombatEvent
{
    public const EVENT_STREAM = 'attack-blocked';

    protected CombatAttackInterface $combatAttack;
    protected CombatantInterface $attacker;
    protected CombatantInterface $target;
    protected int $moment;

    public function __construct(CombatAttackInterface $combatAttack, CombatantInterface $attacker, CombatantInterface $combatant, int $moment)
    {
        $this->combatAttack = $combatAttack;
        $this->target = $combatant;
        $this->attacker = $attacker;
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
     * @return CombatAttackInterface
     */
    public function getCombatAttack(): CombatAttackInterface
    {
        return $this->combatAttack;
    }

    /**
     * @return CombatantInterface
     */
    public function getAttacker(): CombatantInterface
    {
        return $this->attacker;
    }

    /**
     * @return CombatantInterface
     */
    public function getTarget(): CombatantInterface
    {
        return $this->target;
    }
}
