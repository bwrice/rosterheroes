<?php

namespace App\Http\Controllers;

use App\Domain\Actions\JoinSkirmishAction;
use App\Domain\Actions\LeaveSkirmishAction;
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
     * @param JoinSkirmishAction $domainAction
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store($stopUuid, Request $request, JoinSkirmishAction $domainAction)
    {
        return $this->handleRequest($stopUuid, $request, function (CampaignStop $campaignStop, Skirmish $skirmish) use ($domainAction) {
            $domainAction->execute($campaignStop, $skirmish);
        });
    }

    /**
     * @param $stopUuid
     * @param Request $request
     * @param LeaveSkirmishAction $domainAction
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($stopUuid, Request $request, LeaveSkirmishAction $domainAction)
    {
        return $this->handleRequest($stopUuid, $request, function (CampaignStop $campaignStop, Skirmish $skirmish) use ($domainAction) {
            $domainAction->execute($campaignStop, $skirmish);
        });
    }


    /**
     * @param string $stopUuid
     * @param Request $request
     * @param callable $callable
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function handleRequest(string $stopUuid, Request $request, callable $callable)
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
            $callable($campaignStop, $skirmish);
        } catch (CampaignStopException $exception) {
            throw ValidationException::withMessages([
                'campaign' => $exception->getMessage()
            ]);
        }

        return new CampaignResource($campaignStop->campaign->fresh());
    }
}
