<?php

namespace App\Aggregates;

use App\Domain\Models\Continent;
use App\Domain\Models\Territory;
use App\StorableEvents\ProvinceCreated;
use Spatie\EventProjector\AggregateRoot;

final class ProvinceAggregate extends AggregateRoot
{
    public function createProvince(string $name, string $color, string $vectorPaths, Continent $continent, Territory $territory)
    {
        $this->recordThat(new ProvinceCreated(
            $name,
            $color,
            $vectorPaths,
            $continent->id,
            $territory->id
        ));

        return $this;
    }
}
