<?php

namespace App\Http\Controllers;

use App\Exceptions\CampaignFoundForOtherContinentException;
use App\Exceptions\MaxQuestsException;
use App\Quest;
use App\Squad;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SquadQuestController extends Controller
{
    public function store($squadUuid, $questUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $quest = Quest::uuidOrFail($questUuid);

        try {
            $squad->joinQuest($quest);
        } catch ( CampaignFoundForOtherContinentException $exception ) {
            throw ValidationException::withMessages([
                'campaign' => "Campaign already exists on " . $exception->getExistingContinent()->name
            ]);
        } catch(MaxQuestsException $exception) {
            throw ValidationException::withMessages([
                'quests' => $exception->getMessage()
            ]);
        }
    }
}
