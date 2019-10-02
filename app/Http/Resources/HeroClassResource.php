<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroClass;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HeroClassResource
 * @package App\Http\Resources
 *
 * @mixin HeroClass
 */
class HeroClassResource extends JsonResource
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
            'name' => $this->name,
            'iconSrc' => $this->getIconSrc()
        ];
    }
}
