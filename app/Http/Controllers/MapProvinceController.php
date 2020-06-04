<?php

namespace App\Http\Controllers;

use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Http\Resources\ExploredProvinceResource;
use App\Policies\SquadPolicy;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class MapProvinceController extends Controller
{
    public function show($provinceSlug)
    {
        $province = Province::findSlugOrFail($provinceSlug);

        return new ExploredProvinceResource($province);
    }
}
