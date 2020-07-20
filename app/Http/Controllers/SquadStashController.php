<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Http\Resources\GlobalStashResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class SquadStashController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $stashes = $squad->stashes()
            ->withCount('items')
            ->with(['province'])->get();

        $filtered = $stashes->filter(function (Stash $stash) {
            return $stash->items_count > 0;
        });

        return GlobalStashResource::collection($filtered);
    }
}
