<?php

namespace App\Http\Resources;

use App\Domain\Models\Spell;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SpellResource
 * @package App\Http\Resources
 *
 * @mixin Spell
 */
class SpellResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'manaCost' => $this->manaCost(),
            'measurableBoosts' => MeasurableBoostResource::collection($this->measurableBoosts)
        ];
    }
}
