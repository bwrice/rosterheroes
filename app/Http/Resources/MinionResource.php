<?php

namespace App\Http\Resources;

use App\Domain\Models\Minion;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MinionResource
 * @package App\Http\Resources
 *
 * @mixin Minion
 */
class MinionResource extends JsonResource
{
    /** @var Minion */
    public $resource;

    /**
     * @var int|null
     */
    protected $count;

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
            'name' => $this->name,
            'slug' => $this->slug,
            'combatPositionID' => $this->combat_position_id,
            'enemyTypeID' => $this->enemy_type_id,
            'level' => $this->level,
            'startingHealth' => $this->getStartingHealth(),
            'startingStamina' => $this->getStartingStamina(),
            'startingMana' => $this->getStartingMana(),
            'protection' => $this->getProtection(),
            'blockChance' => round($this->getBlockChance(), 2),
            'attacks' => AttackResource::collection($this->attacks)->collection->each(function (AttackResource $attackResource) {
                $attackResource->setHasAttacks($this->resource);
            }),
            'count' => $this->when(! is_null($this->count), $this->count)
        ];
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(int $count)
    {
        $this->count = $count;
        return $this;
    }
}
