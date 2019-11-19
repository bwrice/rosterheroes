<?php

namespace App\Http\Controllers;

use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class EnlistForQuestController extends Controller
{
    public function __invoke($squadSlug, Request $request)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $quest = Quest::findUuidOrFail($request->quest);
    }
}
