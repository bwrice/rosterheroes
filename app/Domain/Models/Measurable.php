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

    /**
     * @param array $attributes
     * @return Measurable|null
     * @throws \Exception
     */
    public static function createWithAttributes(array $attributes)
    {
        $uuid = (string) Uuid::uuid4();

        $attributes['uuid'] = $uuid;

        event(new MeasurableCreationRequested($attributes));

        return self::uuid($uuid);
    }
}
