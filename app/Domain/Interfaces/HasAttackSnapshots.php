<?php


namespace App\Domain\Interfaces;


use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasAttackSnapshots extends HasAttacks
{
    public function attackSnapshots(): MorphMany;

    public function getUuid(): string;
}
