<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\BoostsMeasurables;
use App\Domain\Models\MeasurableBoost;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MeasurableBoostResource
 * @package App\Http\Resources
 *
 * @mixin MeasurableBoost
 */
class MeasurableBoostResource extends JsonResource
{
    /** @var BoostsMeasurables */
    protected $boostsMeasurables;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'measurableType' => new MeasurableTypeResource($this->measurableType),
            'boost_level' => $this->boost_level,
            'boostAmount' => $this->getBoostAmount($this->boostsMeasurables),
            'description' => $this->getDescription($this->boostsMeasurables)
        ];
    }

    /**
     * @param BoostsMeasurables $boostsMeasurables
     * @return MeasurableBoostResource
     */
    public function setBoostsMeasurables(BoostsMeasurables $boostsMeasurables): MeasurableBoostResource
    {
        $this->boostsMeasurables = $boostsMeasurables;
        return $this;
    }
}
