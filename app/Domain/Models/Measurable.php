<?php

namespace App\Domain\Models;

use App\Domain\Models\EventSourcedModel;
use App\Events\MeasurableCreationRequested;
use App\Domain\Models\MeasurableType;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class Measurable
 * @package App
 *
 * @property int $id
 * @property int $measurable_type_id
 */
class Measurable extends EventSourcedModel
{
    protected $guarded = [];

    public function measurableType()
    {
        return $this->belongsTo(MeasurableType::class);
    }

    public function hasMeasurables()
    {
        return $this->morphTo();
    }
}
