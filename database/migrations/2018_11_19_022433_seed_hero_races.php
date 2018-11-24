<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedHeroRaces extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\HeroRace::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\HeroRace::HUMAN,
            \App\HeroRace::ELF,
            \App\HeroRace::DWARF,
            \App\HeroRace::ORC
        ];
    }
}