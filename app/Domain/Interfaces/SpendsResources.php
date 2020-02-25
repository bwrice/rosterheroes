<?php


namespace App\Domain\Interfaces;


interface SpendsResources
{
    public function getCurrentStamina(): int;

    public function getCurrentMana(): int;

    public function setCurrentStamina(int $amount);

    public function setCurrentMana(int $amount);
}
