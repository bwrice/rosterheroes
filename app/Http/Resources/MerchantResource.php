<?php

namespace App\Http\Resources;

use App\Domain\Interfaces\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MerchantResource
 * @package App\Http\Resources
 */
class MerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Merchant $merchant */
        $merchant = $this;
        return [
            'slug' => $merchant->getSlug(),
            'type' => $merchant->getMerchantType()
        ];
    }
}
