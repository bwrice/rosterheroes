<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/23/19
 * Time: 9:51 PM
 */

namespace App\Domain\QueryBuilders;


use App\Domain\Models\Position;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PositionQueryBuilder
 * @package App\Domain\QueryBuilders
 *
 * @method Position|object|static|null first($columns = ['*'])
 */
class PositionQueryBuilder extends Builder
{
    public function forSports(array $sportNames)
    {
        return $this->whereHas('sport', function(Builder $query) use ($sportNames) {
            return $query->whereIn('name', $sportNames);
        });
    }
}