<?php

namespace App\Http\Controllers;

use App\Domain\Actions\AddSkirmishToCampaignStopAction;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Skirmish;
use App\Exceptions\CampaignStopException;
use App\Http\Resources\CampaignResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CampaignStopSkirmishController extends Controller
{
    /**
     * @param $stopUuid
     * @param Request $request
     * @param AddSkirmishToCampaignStopAction $domainAction
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store($stopUuid, Request $request, AddSkirmishToCampaignStopAction $domainAction)
    {
        /** @var CampaignStop $campaignStop */
        $campaignStop = CampaignStop::uuid($stopUuid)->with([
            'campaign.squad',
            'quest',
            'skirmishes'
        ])->firstOrFail();

        $this->authorize(SquadPolicy::MANAGE, $campaignStop->campaign->squad);

        /** @var Skirmish $skirmish */
        $skirmish = Skirmish::uuid($request->skirmish)->with([
            'quest'
        ])->firstOrFail();

        try {
            $domainAction->execute($campaignStop, $skirmish);
        } catch (CampaignStopException $exception) {
            throw ValidationException::withMessages([
                'campaign' => $exception->getMessage()
            ]);
        }

        return new CampaignResource($campaignStop->campaign->fresh());
    }
}
