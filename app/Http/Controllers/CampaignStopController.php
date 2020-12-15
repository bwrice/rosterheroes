<?php

namespace App\Http\Controllers;

use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Http\Resources\HistoricCampaignStopResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class CampaignStopController extends Controller
{
    public function index($campaignUuid)
    {
        $campaign = Campaign::findUuidOrFail($campaignUuid);
        $this->authorize(SquadPolicy::MANAGE, $campaign->squad);

        $campaignStops = $campaign->campaignStops()->with(CampaignStop::historicResourceRelations())->get();
        return HistoricCampaignStopResource::collection($campaignStops);
    }
}
