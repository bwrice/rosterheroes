<?php

use App\Domain\Models\TargetRange;
use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedTargetRanges extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return TargetRange::class;
    }

    public function getSeedNames(): array
    {
        return [
            TargetRange::MELEE,
            TargetRange::MID_RANGE,
            TargetRange::LONG_RANGE
        ];
    }
}
