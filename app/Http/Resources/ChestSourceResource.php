<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\RewardsChests;
use Illuminate\Http\Resources\Json\JsonResource;

class ChestSourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var RewardsChests $source */
        $source = $this;
        return [
            'type' => $source->getChestSourceType(),
            'name' => $source->getChestSourceName()
        ];
    }
}
