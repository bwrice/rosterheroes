<?php

namespace App\Http\Controllers;

use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Http\Resources\ExploredProvinceResource;
use App\Policies\SquadPolicy;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class ExploreProvinceController extends Controller
{
    public function show($squadSlug, $provinceSlug)
    {
        $province = Province::findSlugOrFail($provinceSlug);
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $province->load(['stashes' => function (HasMany $builder) use ($squad) {
            return $builder->where('squad_id', '=', $squad->id);
        }]);
        return new ExploredProvinceResource($province);
    }
}
