<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedHeroRanks extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\HeroRank::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\HeroRank::PRIVATE,
            \App\Domain\Models\HeroRank::CORPORAL,
            \App\Domain\Models\HeroRank::SERGEANT
        ];
    }
}
