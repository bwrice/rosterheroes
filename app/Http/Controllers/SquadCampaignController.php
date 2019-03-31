<?php

namespace App\Http\Controllers;

use App\Exceptions\CampaignExistsException;
use App\Exceptions\WeekLockedException;
use App\Domain\Models\Squad;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SquadCampaignController extends Controller
{
    public function store($squadUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        try {

            $campaign = $squad->createCampaign();
            return response([], 201);

        } catch (CampaignExistsException $exception) {
            return ValidationException::withMessages([
                'campaign' => 'Campaign already exists for ' . $squad->name . ' on continent ' . $exception->getCampaign()->continent->name
            ]);
        } catch (WeekLockedException $exception) {
            throw ValidationException::withMessages([
                'week' => $exception->getMessage()
            ]);
        }
    }
}
