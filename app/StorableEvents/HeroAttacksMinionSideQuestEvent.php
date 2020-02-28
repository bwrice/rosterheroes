<?php


namespace App\StorableEvents;


use App\Domain\Combat\Attacks\HeroCombatAttackDataMapper;

abstract class HeroAttacksMinionSideQuestEvent extends StorableSideQuestEvent
{
    public function getHeroCombatAttackData()
    {
        return $this->data['heroCombatAttack'];
    }

    public function getDamage()
    {
        if (array_key_exists('damage', $this->data)) {
            return $this->data['damage'];
        }
        return 0;
    }
}
