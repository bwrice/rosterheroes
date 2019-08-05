<?php


namespace App\Domain\Interfaces;


interface HasGold
{
    public function getAvailableGold(): int;

    public function decreaseGold(int $amount);
}
