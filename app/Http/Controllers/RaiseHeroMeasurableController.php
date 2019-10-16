<?php

namespace App\Http\Controllers;

use App\Domain\Actions\RaiseMeasurableAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Exceptions\RaiseMeasurableException;
use App\Http\Resources\HeroResource;
use App\Http\Resources\MeasurableResource;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RaiseHeroMeasurableController extends Controller
{
    public function show($heroSlug, Request $request)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);
        $measurable = $hero->getMeasurable($request->type);
        $amount = (int) $request->get('amount');
        $amount = $amount && ($amount >= 1 && $amount <= 100) ? $amount : 1;
        return $measurable->getCostToRaise($amount);
    }

    public function store($heroSlug, Request $request, RaiseMeasurableAction $raiseMeasurableAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);
        $measurable = $hero->getMeasurable($request->post('type'));
        $amount = (int) $request->post('amount');
        $amount = $amount && ($amount >= 1 && $amount <= 100) ? $amount : 1;

        try {
            $raiseMeasurableAction->execute($measurable, $amount);

        } catch (RaiseMeasurableException $exception) {
            throw ValidationException::withMessages([
                'raiseMeasurable' => $exception->getMessage()
            ]);
        }

        return new HeroResource($hero->fresh(Hero::heroResourceRelations()));
    }
}
