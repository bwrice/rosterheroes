<?php

namespace App\Http\Controllers;

use App\Domain\Models\Campaign;
use App\Domain\Models\Squad;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class CurrentCampaignController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $campaign = Campaign::query()->currentForSquad($squad->id)->first();
        if (! $campaign) {
            return response()->json([
                'data' => null
            ], 200);
        }
    }
}
