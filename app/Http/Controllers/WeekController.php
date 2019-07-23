<?php

namespace App\Http\Controllers;

use App\Domain\Models\Week;
use App\Http\Resources\WeekResource;

class WeekController extends Controller
{
    public function show($weekUuid)
    {
        if ($weekUuid === 'current') {
            $week = Week::current();
        } else {
            $week = Week::uuid($weekUuid);
        }
        return new WeekResource($week);
    }
}
