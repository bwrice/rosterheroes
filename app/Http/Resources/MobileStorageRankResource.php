<?php

namespace App\Http\Resources;

use App\Domain\Models\MobileStorageRank;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MobileStorageRankResource
 * @package App\Http\Resources
 *
 * @mixin MobileStorageRank
 */
class MobileStorageRankResource extends JsonResource
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
            'name' => $this->name
        ];
    }
}
