<?php


namespace App\Services;

class FantasyPower
{
    public function calculate(float $totalPoints)
    {
        $fantasyPower = 0;
        $coefficient = 1;
        $remaining = $totalPoints;
        while ($remaining > 0) {
            $pointsToMultiply = $remaining < 10 ? $remaining : 10;
            $fantasyPower += ($coefficient * $pointsToMultiply);
            $remaining -= 10;
            $coefficient *= .8;
        }
        return $fantasyPower;
    }
}
