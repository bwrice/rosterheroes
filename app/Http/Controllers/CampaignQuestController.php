<?php

namespace App\Http\Controllers;

use App\Domain\Models\Campaign;
use App\Exceptions\InvalidContinentException;
use App\Exceptions\InvalidProvinceException;
use App\Exceptions\MaxQuestsException;
use App\Exceptions\QuestCompletedException;
use App\Exceptions\WeekLockedException;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CampaignQuestController extends Controller
{
    public function store($campaignUuid, $questUuid)
    {
        $campaign = Campaign::uuidOrFail($campaignUuid);
        $quest = Quest::uuidOrFail($questUuid);

        try {
            $campaign->addQuest($quest);
            return response()->json([], 201);
        } catch (InvalidContinentException $exception) {
            throw ValidationException::withMessages([
                'campaign' => "Campaign already exists on " . $exception->getInvalidContinent()->name
            ]);
        } catch (MaxQuestsException $exception) {
            throw ValidationException::withMessages([
                'quests' => $exception->getMessage()
            ]);
        } catch (InvalidProvinceException $exception) {
            throw ValidationException::withMessages([
                'province' => 'Must be at province: ' . $quest->province->name . " to join this quest: " . $quest->name
            ]);
        } catch (QuestCompletedException $exception) {
            throw ValidationException::withMessages([
                'quest' => $exception->getQuest()->name . " has already been completed"
            ]);
        } catch (WeekLockedException $exception) {
            throw ValidationException::withMessages([
                'week' => $exception->getMessage()
            ]);
        }
    }
}
