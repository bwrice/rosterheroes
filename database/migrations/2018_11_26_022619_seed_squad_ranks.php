<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedSquadRanks extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\SquadRank::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\SquadRank::CREW,
            \App\Domain\Models\SquadRank::TROUPE,
            \App\Domain\Models\SquadRank::GANG,
            \App\Domain\Models\SquadRank::POSSE,
            \App\Domain\Models\SquadRank::CLAN,
            \App\Domain\Models\SquadRank::BATTALION,
            \App\Domain\Models\SquadRank::LEGION
        ];
    }
}