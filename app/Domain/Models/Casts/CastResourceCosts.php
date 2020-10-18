<?php


namespace App\Domain\Models\Casts;


use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\Json\ResourceCosts\ResourceCost;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

class CastResourceCosts implements CastsAttributes
{

    /**
     * Transform the attribute from the underlying model values.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $resourceCosts
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $resourceCosts, array $attributes)
    {
        return (collect(json_decode($resourceCosts, true)))->map(function ($resourceCostArray) {
            if ($resourceCostArray['type'] === ResourceCost::FIXED) {
                return new FixedResourceCost($resourceCostArray['resource'], $resourceCostArray['amount']);
            }
            return new PercentResourceCost($resourceCostArray['resource'], $resourceCostArray['percent']);
        });
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $resourceCosts
     * @param array $attributes
     * @return mixed
     */
    public function set($model, string $key, $resourceCosts, array $attributes)
    {
        if (! $resourceCosts instanceof Collection) {
            throw new \InvalidArgumentException("resource costs my be a collection");
        }

        $resourceCosts->each(function ($value) {
            if (! $value instanceof ResourceCost) {
                throw new \InvalidArgumentException("resource cost must be of class Resource Cost");
            }
        });

        return json_encode($resourceCosts->toArray());
    }
}
