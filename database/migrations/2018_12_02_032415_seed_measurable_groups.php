<?php

use App\Helpers\ModelNameSeeding\ModelNameSeederMigration;

class SeedMeasurableGroups extends ModelNameSeederMigration
{
    protected function getModelClass(): string
    {
        return \App\MeasurableGroup::class;
    }

    public function getSeedNames(): array
    {
        return [
            \App\MeasurableGroup::ATTRIBUTE,
            \App\MeasurableGroup::RESOURCE,
            \App\MeasurableGroup::QUALITY
        ];
    }
}