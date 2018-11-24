<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedHeroRanks extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\HeroRank::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\HeroRank::PRIVATE,
            \App\HeroRank::CORPORAL,
            \App\HeroRank::SERGEANT
        ];
    }
}
