<?php


namespace App\Domain\Combat\Events;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;

class AttackDamagesCombatant implements CombatEvent
{
    public const EVENT_STREAM = 'attack-damages-combatant';

    protected CombatAttackInterface $combatAttack;
    protected Combatant $combatant;
    protected int $moment, $damage;

    public function __construct(CombatAttackInterface $combatAttack, Combatant $combatant, int $damage, int $moment)
    {
        $this->combatAttack = $combatAttack;
        $this->combatant = $combatant;
        $this->damage = $damage;
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

    public function getDamage(): int
    {
        return $this->damage;
    }
}
