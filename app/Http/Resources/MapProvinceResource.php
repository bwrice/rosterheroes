<?php

namespace App\Http\Resources;

use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ExploredProvinceResource
 * @package App\Http\Resources
 *
 * @mixin Province
 */
class MapProvinceResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {

        return [
            'provinceUuid' => $this->uuid,
            'provinceSlug' => $this->slug,
            'questsCount' => $this->quests()->count(),
            'squadsCount' => $this->squads()->count(),
            'hasShops' => $this->hasShops()
        ];
    }
}
