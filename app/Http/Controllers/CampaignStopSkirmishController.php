<?php

namespace App\Http\Controllers;

use App\Domain\Models\CampaignStop;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class CampaignStopSkirmishController extends Controller
{
    public function store($stopUuid)
    {
        /** @var CampaignStop $campaignStop */
        $campaignStop = CampaignStop::uuid($stopUuid)->with([
            'campaign.squad',
            'quest'
        ])->firstOrFail();
        $this->authorize(SquadPolicy::MANAGE, $campaignStop->campaign->squad);
    }
}
