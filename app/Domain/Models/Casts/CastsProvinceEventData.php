<?php

namespace App\Domain\Models\Casts;

use App\Domain\Models\Json\ProvinceEventData\ProvinceEventData;
use App\Domain\Models\Json\ProvinceEventData\SquadEntersProvince;
use App\Domain\Models\ProvinceEvent;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Date;

class CastsProvinceEventData implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  {
     * @param  array  $attributes
     * @return mixed
     * @throws \Exception
     */
    public function get($model, $key, $data, $attributes)
    {
        $dataArray = json_decode($data, true);
        /** @var ProvinceEvent $model */
        switch ($model->event_type) {
            case ProvinceEvent::TYPE_SQUAD_ENTERS_PROVINCE:
                return new SquadEntersProvince($model->province, $model->happened_at, $dataArray['squad_id'], $dataArray['gold_cost']);
        }
        throw new \Exception("Uknown event-type: " . $model->event_type . " for Province Events");
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        /** @var ProvinceEventData $value */
        return $value->toJson();
    }
}
