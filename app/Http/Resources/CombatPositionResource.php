<?php

namespace App\Http\Resources;

use App\Domain\Models\CombatPosition;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TargetRangeResource
 * @package App\Http\Resources
 *
 * @mixin CombatPosition
 */
class CombatPositionResource extends JsonResource
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
            'attacker_icon' => $this->attackerIcon(),
            'target_icon' => $this->targetIcon()
        ];
    }
}
