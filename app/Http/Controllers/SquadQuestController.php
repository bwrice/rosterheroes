<?php

namespace App\Http\Controllers;

use App\Domain\Actions\JoinQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Exceptions\CampaignException;
use App\Http\Resources\CampaignResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SquadQuestController extends Controller
{
    /**
     * @param $squadSlug
     * @param Request $request
     * @param JoinQuestAction $domainAction
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store($squadSlug, Request $request, JoinQuestAction $domainAction)
    {
        return $this->handleRequest($squadSlug, $request, function(Squad $squad, Quest $quest) use ($domainAction) {
            $domainAction->execute($squad, $quest);
        });
    }

    /**
     * @param $squadSlug
     * @param Request $request
     * @param callable $callable
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function handleRequest($squadSlug, Request $request, callable $callable)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $quest = Quest::findUuidOrFail($request->quest);

        try {
            $callable($squad, $quest);

        } catch (CampaignException $exception) {
            throw ValidationException::withMessages([
                'campaign' => $exception->getMessage()
            ]);
        }

        return new CampaignResource($squad->fresh()->getCurrentCampaign(Campaign::campaignResourceRelations()));
    }
}
