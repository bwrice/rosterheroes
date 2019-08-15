<?php

namespace App\Domain\Models;

use App\Domain\Interfaces\HasMeasurables;
use App\Domain\Models\EventSourcedModel;
use App\Domain\Models\MeasurableType;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class Measurable
 * @package App
 *
 * @property int $id
 * @property int $measurable_type_id
 * @property int $amount_raised
 *
 * @property HasMeasurables $hasMeasurables
 * @property MeasurableType $measurableType
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

    public function getCostToRaise(): int
    {
        return $this->hasMeasurables->costToRaiseMeasurable($this);
    }

    public function getCurrentAmount(): int
    {
        return $this->hasMeasurables->getCurrentMeasurableAmount($this);
    }

    public function getBaseAmount(): int
    {
        return $this->measurableType->getBehavior()->getBaseAmount();
    }

    public function getMeasurableImportanceWeight()
    {
        return $this->measurableType->getBehavior()->getMeasurableImportanceWeight();
    }
}
