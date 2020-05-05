<?php

namespace App\Http\Resources;

use App\Domain\Models\Province;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ExploredProvinceResource
 * @package App\Http\Resources
 *
 * @mixin Province
 */
class ExploredProvinceResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        if ($this->stashes->count() > 1) {
            throw new \Exception(self::class . " should be used in conjunction with a single stash belonging to a specific squad");
        }

        $squadStash = $this->stashes->first();

        return [
            'provinceUuid' => $this->uuid,
            'squadStash' => $squadStash ? new CompactStash($squadStash) : null
        ];
    }
}
