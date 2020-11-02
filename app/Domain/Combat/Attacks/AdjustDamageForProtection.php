<?php


namespace App\Domain\Combat\Attacks;


class AdjustDamageForProtection
{
    public const BASE_FACTOR = 500;

    public function execute(int $initialDamage, int $protection)
    {
        $multiplier = self::BASE_FACTOR / (self::BASE_FACTOR + $protection);
        return (int) ceil($multiplier * $initialDamage);
    }
}
