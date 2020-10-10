<?php


namespace App\Domain\Combat\Events;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatantInterface;

class AttackBlocked implements CombatEvent
{
    public const EVENT_STREAM = 'attack-blocked';

    protected CombatAttackInterface $combatAttack;
    protected CombatantInterface $combatant;
    protected int $moment;

    public function __construct(CombatAttackInterface $combatAttack, CombatantInterface $combatant, int $moment)
    {
        $this->combatAttack = $combatAttack;
        $this->combatant = $combatant;
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
}
