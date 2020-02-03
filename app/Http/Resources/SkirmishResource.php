<?php

namespace App\Http\Resources;

use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SkirmishResource
 * @package App\Http\Resources
 *
 * @mixin SideQuest
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
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'name' => $this->name,
            'minions' => $this->minions->map(function (Minion $minion) {
                $resource = new MinionResource($minion);
                $resource->setCount($minion->pivot->count);
                return $resource;
            }),
            'difficulty' => $this->difficulty()
        ];
    }
}
