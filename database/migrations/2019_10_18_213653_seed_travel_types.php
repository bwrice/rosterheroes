<?php

use App\Domain\Models\TravelType;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedTravelTypes extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return TravelType::class;
    }

    public function getSeedNames(): array
    {
        return [
            TravelType::STATIONARY,
            TravelType::BORDER,
            TravelType::TERRITORY,
            TravelType::CONTINENT,
            TravelType::REALM
        ];
    }
}
