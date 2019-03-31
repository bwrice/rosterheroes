<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedMeasurableGroups extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\Domain\Models\MeasurableGroup::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\Domain\Models\MeasurableGroup::ATTRIBUTE,
            \App\Domain\Models\MeasurableGroup::RESOURCE,
            \App\Domain\Models\MeasurableGroup::QUALITY
        ];
    }
}