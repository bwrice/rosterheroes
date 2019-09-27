<?php

namespace App\Http\Controllers;

use App\Domain\Models\Squad;
use App\Http\Resources\MobileStorageRankResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class MobileStorageController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        return new MobileStorageRankResource($squad);
    }
}
