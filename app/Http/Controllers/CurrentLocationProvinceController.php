<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Http\Resources\ProvinceResource;
use App\Policies\SquadPolicy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class CurrentLocationProvinceController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $currentLocationProvince = $squad->province()->with([
            'borders' => function(BelongsToMany $builder) {
                return $builder->select('uuid');
            }
        ])->first();

        return new ProvinceResource($currentLocationProvince);
    }
}
