<?php


namespace App\Domain\Interfaces;


interface SpendsResources
{
    public function getCurrentStamina(): int;

    public function getCurrentMana(): int;

    public function spendStamina(int $amount);

    public function spendMana(int $amount);
}
