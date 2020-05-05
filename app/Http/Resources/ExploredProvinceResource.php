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
class ExploredProvinceResource extends JsonResource
{
    /**
     * @var Squad
     */
    protected $squad;

    public function __construct(Province $province, Squad $squad)
    {
        parent::__construct($province);
        $this->squad = $squad;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        $this->squad->loadMissing([
            'stashes' => function (HasMany $builder) {
                return $builder->where('province_id', '=', $this->id);
            }
        ]);

        $stashAtProvince = $this->squad->stashes->first();

        return [
            'provinceUuid' => $this->uuid,
            'squadStash' => $stashAtProvince ? new CompactStash($stashAtProvince) : null
        ];
    }
}
