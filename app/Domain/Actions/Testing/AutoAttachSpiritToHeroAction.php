<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Models\Hero;

class AutoAttachSpiritToHeroAction
{
    public function execute(Hero $hero)
    {
        return $hero->fresh();
    }
}
