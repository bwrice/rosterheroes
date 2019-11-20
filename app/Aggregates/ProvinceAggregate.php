<?php

namespace App\Aggregates;

use App\Domain\Models\Continent;
use App\Domain\Models\Territory;
use App\StorableEvents\ProvinceCreated;
use Spatie\EventSourcing\AggregateRoot;

final class ProvinceAggregate extends AggregateRoot
{
    public function createProvince(string $name, string $color, array $viewBox, string $vectorPaths, Continent $continent, Territory $territory)
    {
        $this->recordThat(new ProvinceCreated(
            $name,
            $color,
            $viewBox,
            $vectorPaths,
            $continent->id,
            $territory->id
        ));

        return $this;
    }
}
