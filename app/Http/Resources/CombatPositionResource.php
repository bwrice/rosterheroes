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
            'id' => $this->id,
            'name' => $this->name,
            'attackerSVG' => $this->getSVG(true),
            'targetSVG' => $this->getSVG(false),
            'outerRadius' => $this->getBehavior()->getOuterRadius(),
            'innerRadius' => $this->getBehavior()->getInnerRadius(),
            'allyColor' => $this->getBehavior()->getAllyColor(),
            'enemyColor' => $this->getBehavior()->getEnemyColor()
        ];
    }
}
