<?php


namespace App\Domain\Interfaces;


use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasAttackSnapshots
{
    public function attackSnapshots(): MorphMany;
}
