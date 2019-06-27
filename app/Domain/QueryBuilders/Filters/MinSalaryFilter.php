<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 6/26/19
 * Time: 9:47 PM
 */

namespace App\Domain\QueryBuilders\Filters;


use App\Domain\Interfaces\SalaryQueryable;
use App\Exceptions\InvalidFilterBuilderException;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class MinSalaryFilter implements Filter
{

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $model = $query->getModel();
        $subQuery = $model->newEloquentBuilder($query->getQuery())->setModel($model);
        if ( ! ($subQuery instanceof SalaryQueryable)) {
            throw new InvalidFilterBuilderException(static::class, SalaryQueryable::class, $subQuery);
        }
        return $subQuery->minSalary($value);
    }
}