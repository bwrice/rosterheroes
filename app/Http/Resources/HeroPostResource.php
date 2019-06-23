<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroPost;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HeroPostResource
 * @package App\Http\Resources
 *
 * @mixin HeroPost
 */
class HeroPostResource extends JsonResource
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
            'hero_post_type_id' => $this->hero_post_type_id,
            'hero' => new HeroResource($this->whenLoaded('hero'))
        ];
    }
}
