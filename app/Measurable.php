<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Measurable
 * @package App
 *
 * @property int $id
 * @property int $measurable_type_id
 */
class Measurable extends Model
{
    protected $guarded = [];

    public function measurableType()
    {
        return $this->belongsTo(MeasurableType::class);
    }
}
