<?php

namespace App\Http\Controllers;

use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Http\Resources\QuestResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class CurrentLocationQuestsController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $quests = $squad->province->quests()->with(Quest::resourceRelations())->get();
        return QuestResource::collection($quests);
    }
}
