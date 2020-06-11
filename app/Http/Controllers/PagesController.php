<?php

namespace App\Http\Controllers;

use App\Domain\Behaviors\MeasurableTypes\Attributes\AttributeBehavior;
use App\Domain\Behaviors\MeasurableTypes\Resources\ResourceBehavior;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function faq() {

        $heroClasses = HeroClass::requiredStarting()->get();
        $heroRaces = HeroRace::starting()->get();

        $measurableTypes = MeasurableType::all();

        $attributeTypes = $measurableTypes->filter(function (MeasurableType $measurableType) {
            return $measurableType->getBehavior()->getGroupName() === AttributeBehavior::GROUP_NAME;
        });

        $resourceTypes = $measurableTypes->filter(function (MeasurableType $measurableType) {
            return $measurableType->getBehavior()->getGroupName() === ResourceBehavior::GROUP_NAME;
        });

        return view('faq', [
            'heroClasses' => $heroClasses,
            'heroRaces' => $heroRaces,
            'attributeTypes' => $attributeTypes,
            'resourceTypes' => $resourceTypes
        ]);
    }
}
