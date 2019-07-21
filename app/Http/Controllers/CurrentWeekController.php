<?php

namespace App\Http\Controllers;

use App\Domain\Models\Week;
use App\Http\Resources\WeekResource;

class CurrentWeekController extends Controller
{
    public function __invoke()
    {
        $week = Week::current();
        return new WeekResource($week);
    }
}
