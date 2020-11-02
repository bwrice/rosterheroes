<?php

namespace App\Http\Controllers;

use App\Domain\Models\Campaign;
use App\Domain\Models\Squad;
use App\Facades\CurrentWeek;
use App\Http\Resources\HistoricCampaignResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class CampaignHistoryController extends Controller
{
    public function index($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $campaigns = $squad->campaigns()
            ->where('week_id', '!=', CurrentWeek::id())
            ->orderByDesc('id')
            ->paginate();

        return HistoricCampaignResource::collection($campaigns);
    }
}
