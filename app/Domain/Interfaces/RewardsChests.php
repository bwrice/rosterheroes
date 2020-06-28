<?php


namespace App\Domain\Interfaces;


interface RewardsChests extends Morphable
{
    public function getChestSourceType(): string;

    public function getChestSourceName(): string;
}
