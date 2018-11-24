<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedHeroClasses extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\HeroClass::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\HeroClass::WARRIOR,
            \App\HeroClass::RANGER,
            \App\HeroClass::SORCERER
        ];
    }
}
