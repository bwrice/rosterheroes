<?php

namespace App\Http\Resources;

use App\Domain\Models\SideQuestEvent;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SideQuestEventResource
 * @package App\Http\Resources
 *
 * @mixin SideQuestEvent
 */
class SideQuestEventResource extends JsonResource
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
            'moment' => $this->moment,
            'eventType' => $this->event_type,
            'data' => $this->data
        ];
    }
}
