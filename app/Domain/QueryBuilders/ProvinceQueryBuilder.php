<?php


namespace App\Domain\QueryBuilders;


use App\Domain\Models\Continent;
use App\Domain\Models\Province;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProvinceQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method Province|mixed first($columns = ['*'])
 */
class ProvinceQueryBuilder extends Builder
{
    public function ofContinent(int $continentID)
    {
        return $this->where('continent_id', '=', $continentID);
    }
}
