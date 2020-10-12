<?php


namespace App\Domain\Combat\Events;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatantInterface;

class AttackKillsCombatant implements CombatEvent
{
    public const EVENT_STREAM = 'attack-damages-combatant';

    protected CombatAttackInterface $combatAttack;
    protected CombatantInterface $attacker;
    protected CombatantInterface $target;
    protected int $moment, $initialDamageToReceive, $actualDamageReceived;

    public function __construct(
        CombatAttackInterface $combatAttack,
        CombatantInterface $attacker,
        CombatantInterface $target,
        int $initialDamageToReceive,
        int $actualDamageReceived,
        int $moment)
    {
        $this->combatAttack = $combatAttack;
        $this->attacker = $attacker;
        $this->target = $target;
        $this->initialDamageToReceive = $initialDamageToReceive;
        $this->actualDamageReceived = $actualDamageReceived;
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

    public function getInitialDamageToReceive(): int
    {
        return $this->initialDamageToReceive;
    }

    public function getActualDamageReceived(): int
    {
        return $this->actualDamageReceived;
    }
}
