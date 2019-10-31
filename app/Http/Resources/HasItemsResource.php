<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\HasItems;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class HasItemsResource
 * @package App\Http\Resources
 *
 * @method JsonResource getHasItemsResource
 * @method string getHasItemsType
 */
class HasItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var HasItems $this */
        return [
            'resource' => $this->getHasItemsResource(),
            'type' => $this->getHasItemsType()
        ];
    }
}
