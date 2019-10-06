<?php

namespace App\Http\Controllers;

use App\Domain\Actions\RaiseMeasurableAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Exceptions\RaiseMeasurableException;
use App\Http\Resources\MeasurableResource;
use App\Policies\MeasurablePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RaiseMeasurableController extends Controller
{
    public function show($measurableUuid, Request $request)
    {
        $measurable = Measurable::findUuidOrFail($measurableUuid);
        $amount = (int) $request->get('amount');
        $amount = $amount && ($amount >= 1 && $amount <= 100) ? $amount : 1;
        return $measurable->getCostToRaise($amount);
    }

    public function store($measurableUuid, Request $request, RaiseMeasurableAction $raiseMeasurableAction)
    {
        $measurable = Measurable::findUuidOrFail($measurableUuid);
        $this->authorize(MeasurablePolicy::RAISE, $measurable);
        $amount = (int) $request->get('amount');
        $amount = $amount && ($amount >= 1 && $amount <= 100) ? $amount : 1;
        Log::debug($amount);
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
