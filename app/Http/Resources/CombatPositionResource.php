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
    protected $attacker = true;

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
            'svg' => $this->getSVG($this->attacker)
        ];
    }

    /**
     * @param bool $attacker
     * @return CombatPositionResource
     */
    public function setAttacker(bool $attacker): CombatPositionResource
    {
        $this->attacker = $attacker;
        return $this;
    }
}
