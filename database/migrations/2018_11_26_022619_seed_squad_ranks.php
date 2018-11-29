<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedSquadRanks extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\SquadRank::class;
    }

    public function getSeedNames(): array
    {
        \App\SquadRank::unguard();
        return [
            \App\SquadRank::CREW,
            \App\SquadRank::TROUPE,
            \App\SquadRank::GANG,
            \App\SquadRank::POSSE,
            \App\SquadRank::CLAN,
            \App\SquadRank::BATTALION,
            \App\SquadRank::LEGION
        ];
    }
}