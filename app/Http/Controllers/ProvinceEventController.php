<?php

namespace App\Http\Controllers;

use App\Domain\Models\ProvinceEvent;
use App\Http\Resources\ProvinceEventResource;
use Illuminate\Http\Request;

class ProvinceEventController extends Controller
{
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
