<?php


namespace App\Domain\QueryBuilders;


use App\Domain\Models\Continent;
use Illuminate\Database\Eloquent\Builder;

class ProvinceQueryBuilder extends Builder
{
    public function withContinent(int $continentID)
    {
        return $this->where('continent_id', '=', $continentID);
    }
}
