<?php

namespace App\Http\Controllers;

use App\Domain\Actions\EnlistForQuestAction;
use App\Domain\Models\Campaign;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Exceptions\CampaignException;
use App\Http\Resources\CampaignResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EnlistForQuestController extends Controller
{
    /**
     * @param $squadSlug
     * @param Request $request
     * @param EnlistForQuestAction $domainAction
     * @return CampaignResource
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($squadSlug, Request $request, EnlistForQuestAction $domainAction)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $quest = Quest::findUuidOrFail($request->quest);

        try {

            $domainAction->execute($squad, $quest);

        } catch (CampaignException $exception) {
            throw ValidationException::withMessages([
                'campaign' => $exception->getMessage()
            ]);
        }

        return new CampaignResource($squad->fresh()->getCurrentCampaign(Campaign::campaignResourceRelations()));
    }
}
