<?php

namespace App\Http\Resources;

use App\Domain\Models\TargetPriority;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TargetPriorityResource
 * @package App\Http\Resources
 *
 * @mixin TargetPriority
 */
class TargetPriorityResource extends JsonResource
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
