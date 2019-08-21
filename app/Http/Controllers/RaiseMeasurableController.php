<?php

namespace App\Http\Controllers;

use App\Domain\Actions\RaiseMeasurableAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Exceptions\RaiseMeasurableException;
use App\Http\Resources\MeasurableResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RaiseMeasurableController extends Controller
{
    public function show($measurableUuid, Request $request)
    {
        $measurable = Measurable::findUuidOrFail($measurableUuid);
        $amount = $request->get('amount');
        $amount = $amount && $amount >= 1 ? (int) $amount : 1;
        return $measurable->getCostToRaise($amount);
    }

    public function store($measurableUuid, Request $request, RaiseMeasurableAction $raiseMeasurableAction)
    {
        $measurable = Measurable::findUuidOrFail($measurableUuid);
        $amount = $request->get('amount');
        try {
            $raiseMeasurableAction->execute($measurable, $amount);

        } catch (RaiseMeasurableException $exception) {
            throw ValidationException::withMessages([
                'raise_measurable' => $exception->getMessage()
            ]);
        }

        return new MeasurableResource($measurable->fresh());
    }
}
