<?php

namespace App\Http\Controllers;

use App\Domain\Models\ProvinceEvent;
use App\Http\Resources\ProvinceEventResource;
use Illuminate\Http\Request;

class ProvinceEventController extends Controller
{
    public function index()
    {
        $provinceEvents = ProvinceEvent::query()
            ->whereIn('event_type', ProvinceEvent::GLOBAL_EVENTS)
            ->orderByDesc('happened_at')
            ->limit(200)
            ->get();

        return ProvinceEventResource::collection($provinceEvents);
    }

    public function show($provinceEventUuid)
    {
        $provinceEvent = ProvinceEvent::findUuidOrFail($provinceEventUuid);
        return [
            'data' => array_merge([
                'provinceEvent' => new ProvinceEventResource($provinceEvent)
            ], $provinceEvent->getSupplementalResourceData())
        ];
    }
}
