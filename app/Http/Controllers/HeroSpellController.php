<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;

class HeroSpellController extends Controller
{
    public function store($heroSlug, Request $request)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);
    }
}
