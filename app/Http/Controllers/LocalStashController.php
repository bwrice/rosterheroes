<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Domain\Models\Stash;
use App\Http\Resources\LocalStashResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class LocalStashController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        return new LocalStashResource($squad->getLocalStash()->load(Stash::getResourceRelations()));
    }
}
