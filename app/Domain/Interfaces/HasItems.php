<?php


namespace App\Domain\Interfaces;


interface HasItems
{
    public function getStrengthAmount(): int;

    public function getValorAmount(): int;

    public function getAgilityAmount(): int;

    public function getFocusAmount(): int;

    public function getAptitudeAmount(): int;

    public function getIntelligenceAmount(): int;
}
