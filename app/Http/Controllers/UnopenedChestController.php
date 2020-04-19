<?php

namespace App\Http\Controllers;

use App\Chest;
use App\Domain\Models\Squad;
use App\Http\Resources\UnopenedChestResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class UnopenedChestController extends Controller
{
    public function index($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $chests = $squad->chests()
            ->with(Chest::unopenedResourceRelations())
            ->whereNull('opened_at')
            ->get();

        return UnopenedChestResource::collection($chests);
    }
}
