<?php

namespace App\Http\Controllers;

use App\Domain\Actions\ChangeHeroCombatPositionAction;
use App\Domain\Models\Hero;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;

class HeroChangeCombatPositionController extends Controller
{
    public function __invoke($heroSlug, Request $request, ChangeHeroCombatPositionAction $domainAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);


    }
}
