<?php

namespace App\Http\Resources;

use App\Domain\Models\Minion;
use App\Domain\Models\Skirmish;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SkirmishResource
 * @package App\Http\Resources
 *
 * @mixin Skirmish
 */
class SkirmishResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'minions' => $this->minions->map(function (Minion $minion) {
                $resource = new MinionResource($minion);
                $resource->setCount($minion->pivot->count);
                return $resource;
            }),
        ];
    }
}
