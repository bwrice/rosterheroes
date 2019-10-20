<?php


namespace App\Domain\Behaviors\EnemyTypes;


class EnemyTypeBehavior
{
    protected $healthModifierBonus = 0;

    public function getStartingHealth(int $level, int $healthRating): int
    {
        return (int) ceil(sqrt($level) * ($level/5) * $healthRating * (1 + $this->healthModifierBonus));
    }
}
