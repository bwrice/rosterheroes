<?php

namespace App\Http\Resources;

use App\Domain\Models\HeroClass;
use App\Domain\Models\RecruitmentCamp;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RecruitmentCampResource
 * @package App\Http\Resources
 *
 * @mixin RecruitmentCamp
 */
class RecruitmentCampResource extends JsonResource
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
            'heroPostTypes' => HeroPostTypeResource::collection($this->heroPostTypes),
            'heroClassIDs' => $this->heroClasses->map(function (HeroClass $heroClass) {
                return $heroClass->id;
            })
        ];
    }
}
