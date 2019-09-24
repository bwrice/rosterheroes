<?php


namespace App\Domain\Interfaces;


interface HasUniqueIdentifier
{
    public function getUniqueIdentifier(): string;
}
