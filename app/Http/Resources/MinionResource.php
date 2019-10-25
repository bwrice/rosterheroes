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
            'level' => $this->level,
            'startingHealth' => $this->getStartingHealth(),
            'protection' => $this->getProtection(),
            'blockChance' => $this->getBlockChance()
        ];
    }
}
