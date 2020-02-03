<?php

namespace App\Http\Controllers;

use App\Domain\Actions\JoinSideQuestAction;
use App\Domain\Actions\LeaveSideQuestAction;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Exceptions\CampaignStopException;
use App\Http\Resources\CampaignResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CampaignStopSideQuestController extends Controller
{
    /**
     * @param $stopUuid
     * @param Request $request
     * @param JoinSideQuestAction $domainAction
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store($stopUuid, Request $request, JoinSideQuestAction $domainAction)
    {
        return $this->handleRequest($stopUuid, $request, function (CampaignStop $campaignStop, SideQuest $skirmish) use ($domainAction) {
            $domainAction->execute($campaignStop, $skirmish);
        });
    }

    /**
     * @param $stopUuid
     * @param Request $request
     * @param LeaveSideQuestAction $domainAction
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($stopUuid, Request $request, LeaveSideQuestAction $domainAction)
    {
        return $this->handleRequest($stopUuid, $request, function (CampaignStop $campaignStop, SideQuest $skirmish) use ($domainAction) {
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

        /** @var SideQuest $skirmish */
        $skirmish = SideQuest::uuid($request->skirmish)->with([
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
