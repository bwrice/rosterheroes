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
        return new ProvinceEventResource($provinceEvent);
    }
}
