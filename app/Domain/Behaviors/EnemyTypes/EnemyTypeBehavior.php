<?php


namespace App\Domain\Behaviors\EnemyTypes;


class EnemyTypeBehavior
{
    protected $healthModifierBonus = 0;
    protected $protectionModifierBonus = 0;

    public function getStartingHealth(int $level, int $healthRating): int
    {
        return (int) ceil(sqrt($level) * ($level/5) * $healthRating * (1 + $this->healthModifierBonus));
    }

    public function getProtection(int $level, int $protectionRating): int
    {
        return (int) ceil(sqrt($level) * ($level/5) * $protectionRating * (1 + $this->protectionModifierBonus));
    }
}
