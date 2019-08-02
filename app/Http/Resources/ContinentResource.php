<?php

namespace App\Http\Resources;

use App\Domain\Models\Continent;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ContinentResource
 * @package App\Http\Resources
 *
 * @mixin Continent
 */
class ContinentResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
