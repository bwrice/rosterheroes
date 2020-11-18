<?php

namespace App\Http\Controllers;

use App\Domain\Models\SideQuestEvent;
use App\Domain\Models\SideQuestResult;
use App\Http\Resources\SideQuestEventResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class SideQuestResultBattlegroundController extends Controller
{
    public function __invoke($sideQuestResultUuid)
    {
        $sideQuestResult = SideQuestResult::findUuidOrFail($sideQuestResultUuid)->load('campaignStop.campaign.squad');
        $this->authorize(SquadPolicy::MANAGE, $sideQuestResult->campaignStop->campaign->squad);

        $battleGroundSetEvent = $sideQuestResult->sideQuestEvents()
            ->where('event_type', '=', SideQuestEvent::TYPE_BATTLEGROUND_SET)
            ->firstOrFail();

        return new SideQuestEventResource($battleGroundSetEvent);
    }
}
