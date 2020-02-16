<?php


namespace App\Services;

use App\Domain\Interfaces\HasFantasyPoints;

class FantasyPower
{
    public function calculate(HasFantasyPoints $hasFantasyPoints)
    {
        $fantasyPower = 0;
        $coefficient = 1;
        $remaining = $hasFantasyPoints->getFantasyPoints();
        while ($remaining > 0) {
            $pointsToMultiply = $remaining < 10 ? $remaining : 10;
            $fantasyPower += ($coefficient * $pointsToMultiply);
            $remaining -= 10;
            $coefficient *= .8;
        }
        return $fantasyPower;
    }
}
