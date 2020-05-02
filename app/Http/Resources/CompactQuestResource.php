<?php

namespace App\Http\Resources;

use App\Domain\Models\Quest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CompactQuestResource
 * @package App\Http\Resources
 *
 * @mixin Quest
 */
class CompactQuestResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'level' => $this->level,
            'provinceID' => $this->province_id,
            'percent' => round($this->percent, 2),
        ];
    }
}
