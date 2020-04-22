<?php

namespace App\Http\Resources;

use App\Domain\DataTransferObjects\OpenedChestResult;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OpenChestResultResource
 * @package App\Http\Resources
 *
 * @mixin OpenedChestResult
 */
class OpenedChestResultResource extends JsonResource
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
            'gold' => $this->getGold(),
            'items' => ItemResource::collection($this->getItems()->load('hasItems'))
        ];
    }
}
