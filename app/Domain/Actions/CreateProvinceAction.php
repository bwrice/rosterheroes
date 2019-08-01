<?php


namespace App\Domain\Actions;


use App\Aggregates\ProvinceAggregate;
use App\Domain\Models\Continent;
use App\Domain\Models\Province;
use App\Domain\Models\Territory;
use Illuminate\Support\Str;

class CreateProvinceAction
{
    /**
     * @param string $name
     * @param string $color
     * @param string $vectorPaths
     * @param Continent $continent
     * @param Territory $territory
     * @return Province
     */
    public function execute(string $name, string $color, string $vectorPaths, Continent $continent, Territory $territory): Province
    {
        $uuid = Str::uuid();

        /** @var ProvinceAggregate $aggregate */
        $aggregate = ProvinceAggregate::retrieve($uuid);
        $aggregate->createProvince(
            $name,
            $color,
            $vectorPaths,
            $continent,
            $territory
        )->persist();

        return Province::uuid($uuid);
    }
}