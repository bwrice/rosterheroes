<?php


namespace App\Domain\Actions;


class CalculateFantasyPower
{
    public function execute(float $fantasyPoints)
    {
        $fantasyPower = 0;
        $coefficient = 1;
        $remaining = $fantasyPoints;
        while ($remaining > 0) {
            $pointsToMultiply = $remaining < 10 ? $remaining : 10;
            $fantasyPower += ($coefficient * $pointsToMultiply);
            $remaining -= 10;
            $coefficient *= .8;
        }
        return $fantasyPower;
    }
}
