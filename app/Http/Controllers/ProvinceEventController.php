<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Http\Resources\ProvinceEventResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class ProvinceEventController extends Controller
{
    public function show($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $province = $squad->province;

        $events = $province->provinceEvents()->with('squad')
            ->where('province_id', '=', $province->id)
            ->orderByDesc('happened_at')->limit(100)
            ->get();

        return ProvinceEventResource::collection($events);
    }
}
