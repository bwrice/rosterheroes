<?php

namespace App\Http\Resources;

use App\Chest;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * Class UnopenedChestResource
 * @package App\Http\Resources
 *
 * @mixin Chest
 */
class UnopenedChestResource extends JsonResource
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
            'description' => $this->getDescription(),
            'sourceDescription' => $this->source ? $this->source->getSourceDescription() : null
        ];
    }
}
