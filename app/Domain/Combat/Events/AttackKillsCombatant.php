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
    protected int $moment, $damage;

    public function __construct(
        CombatAttackInterface $combatAttack,
        CombatantInterface $attacker,
        CombatantInterface $target,
        int $damage,
        int $moment)
    {
        $this->combatAttack = $combatAttack;
        $this->attacker = $attacker;
        $this->target = $target;
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
