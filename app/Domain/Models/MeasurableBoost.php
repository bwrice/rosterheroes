<?php

namespace App\Domain\Models;

use App\Domain\Collections\MeasurableBoostCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MeasurableBoost
 * @package App\Domain\Models
 *
 * @property int $boost_level
 *
 * @property MeasurableType $measurableType
 */
class MeasurableBoost extends Model
{
    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new MeasurableBoostCollection($models);
    }

    public function measurableType()
    {
        return $this->belongsTo(MeasurableType::class);
    }
}
