<?php


namespace App\Domain\Collections;


use App\Domain\Interfaces\HasAttacks;
use App\Domain\Models\Attack;
use App\Domain\Models\CombatPosition;
use Illuminate\Database\Eloquent\Collection;

class AttackCollection extends Collection
{
    public function getDamagePerMoment(float $fantasyPower)
    {
        return $this->sum(function (Attack $attack) use ($fantasyPower) {
            return $attack->getDamagePerMoment($fantasyPower);
        });
    }

    public function withAttackerPosition(CombatPosition $attackerPosition)
    {
        return $this->filter(function (Attack $attack) use ($attackerPosition) {
            return $attack->attacker_position_id === $attackerPosition->id;
        });
    }

    public function staminaPerMoment()
    {
        return $this->sum(function (Attack $attack) {
            return $attack->getStaminaPerMoment();
        });
    }

    public function manaPerMoment()
    {
        return $this->sum(function (Attack $attack) {
            return $attack->getManaPerMoment();
        });
    }

}
