<?php


namespace App\Domain\Collections;


use App\Domain\Interfaces\HasAttacks;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

class AttackCollection extends Collection
{
    public function getDamagePerMoment()
    {
        return $this->sum(function (Attack $attack) {
            $damage = $attack->getDamagePerMoment();
            return $damage;
        });
    }

    public function setHasAttacks(HasAttacks $hasAttacks)
    {
        $this->each(function (Attack $attack) use ($hasAttacks) {
            $attack->setHasAttacks($hasAttacks);
        });
        return $this;
    }

    public function attackerPosition(CombatPosition $attackerPosition)
    {
        return $this->filter(function (Attack $attack) use ($attackerPosition) {
            return $attack->attacker_position_id === $attackerPosition->id;
        });
    }
}
