<?php


namespace App\Domain\Combat\Events;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatantInterface;

class AttackDamagesCombatant implements CombatEvent
{
    public const EVENT_STREAM = 'attack-damages-combatant';

    protected CombatAttackInterface $combatAttack;
    protected CombatantInterface $combatant;
    protected int $moment, $damage;

    public function __construct(CombatAttackInterface $combatAttack, CombatantInterface $combatant, int $damage, int $moment)
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
