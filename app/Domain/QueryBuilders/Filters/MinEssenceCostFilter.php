<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/26/19
 * Time: 9:47 PM
 */

namespace App\Domain\QueryBuilders\Filters;


use App\Domain\Interfaces\EssenceCostQueryable;
use App\Exceptions\InvalidFilterBuilderException;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class MinEssenceCostFilter implements Filter
{

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $model = $query->getModel();
        $subQuery = $model->newEloquentBuilder($query->getQuery())->setModel($model);
        if ( ! ($subQuery instanceof EssenceCostQueryable)) {
            throw new InvalidFilterBuilderException(static::class, EssenceCostQueryable::class, $subQuery);
        }
        return $subQuery->minEssenceCost($value);
    }
}