<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedHeroClasses extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\HeroClass::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\HeroClass::WARRIOR,
            \App\Domain\Models\HeroClass::RANGER,
            \App\Domain\Models\HeroClass::SORCERER
        ];
    }
}
