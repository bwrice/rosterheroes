<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use Illuminate\Http\Request;

class RaiseMeasurableController extends Controller
{
    public function get($measurableUuid, Request $request)
    {
        $measurable = Measurable::findUuidOrFail($measurableUuid);
        $amount = $request->get('amount');
        $amount = $amount && $amount >= 1 ? (int) $amount : 1;
        return $measurable->getCostToRaise($amount);
    }
}
