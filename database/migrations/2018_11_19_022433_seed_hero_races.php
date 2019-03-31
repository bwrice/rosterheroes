<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedHeroRaces extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\HeroRace::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\HeroRace::HUMAN,
            \App\Domain\Models\HeroRace::ELF,
            \App\Domain\Models\HeroRace::DWARF,
            \App\Domain\Models\HeroRace::ORC
        ];
    }
}