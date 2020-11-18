<?php

namespace App\Http\Controllers;

use App\Domain\Models\SideQuestResult;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class SideQuestResultBattlegroundController extends Controller
{
    public function __invoke($sideQuestResultUuid)
    {
        $sideQuestResult = SideQuestResult::findUuidOrFail($sideQuestResultUuid)->load('campaignStop.campaign.squad');
        $this->authorize(SquadPolicy::MANAGE, $sideQuestResult->campaignStop->campaign->squad);
    }
}
