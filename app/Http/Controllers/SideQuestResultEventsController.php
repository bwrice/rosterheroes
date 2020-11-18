<?php

namespace App\Http\Controllers;

use App\Domain\Models\SideQuestResult;
use App\Http\Resources\SideQuestEventResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SideQuestResultEventsController extends Controller
{
    /**
     * @param $sideQuestResultUuid
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index($sideQuestResultUuid)
    {
        $sideQuestResult = SideQuestResult::findUuidOrFail($sideQuestResultUuid);
        $squad = $sideQuestResult->campaignStop->campaign->squad;
        $this->authorize(SquadPolicy::MANAGE, $squad);

        if (! $sideQuestResult->combat_processed_at) {
            throw ValidationException::withMessages([
                'side-quest-result' => 'side quest result not processed yet'
            ]);
        }

        $sideQuestEvents = $sideQuestResult
            ->sideQuestEvents()
            ->orderBy('moment')
            ->paginate();
        return SideQuestEventResource::collection($sideQuestEvents);
    }
}
