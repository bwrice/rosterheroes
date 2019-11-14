<?php

namespace App\Http\Resources;

use App\Domain\Models\Json\ViewBox;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ViewBoxResource
 * @package App\Http\Resources
 *
 * @mixin ViewBox
 */
class ViewBoxResource extends JsonResource
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
            'panX' => $this->getPanX(),
            'panY' => $this->getPanY(),
            'zoomX' => $this->getZoomX(),
            'zoomY' => $this->getZoomY()
        ];
    }
}
