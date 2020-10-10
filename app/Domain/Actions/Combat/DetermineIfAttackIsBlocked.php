<?php


namespace App\Domain\Actions\Combat;


class DetermineIfAttackIsBlocked
{
    /**
     * @param float $blockChancePercent
     * @return bool
     */
    public function execute(float $blockChancePercent)
    {
        return rand(0, 100) <= $blockChancePercent;
    }
}
