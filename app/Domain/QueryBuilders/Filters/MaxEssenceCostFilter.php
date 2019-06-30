<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/26/19
 * Time: 10:17 PM
 */

namespace App\Domain\QueryBuilders\Filters;


use App\Domain\Interfaces\EssenceCostQueryable;
use App\Exceptions\InvalidFilterBuilderException;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class MaxEssenceCostFilter implements Filter
{

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $model = $query->getModel();
        $subQuery = $model->newEloquentBuilder($query->getQuery())->setModel($model);
        if ( ! ($subQuery instanceof EssenceCostQueryable)) {
            throw new InvalidFilterBuilderException(static::class, EssenceCostQueryable::class, $subQuery);
        }
        return $subQuery->maxEssenceCost($value);
    }
}