<?php


namespace App\Domain\Interfaces;


interface Morphable
{
    public function getMorphType(): string;

    public function getMorphID(): int;
}
