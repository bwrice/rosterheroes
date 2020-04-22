<?php

namespace App\Http\Controllers;

use App\Chest;
use App\Domain\Actions\OpenChest;
use App\Http\Resources\OpenedChestResultResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class OpenChestController extends Controller
{
    /**
     * @param $chestUuid
     * @param OpenChest $openChest
     * @return OpenedChestResultResource
     * @throws \Exception
     */
    public function __invoke($chestUuid, OpenChest $openChest)
    {
        $chest = Chest::findUuidOrFail($chestUuid);
        $this->authorize(SquadPolicy::MANAGE, $chest->squad);

        $openChestResult = $openChest->execute($chest);
        return new OpenedChestResultResource($openChestResult);
    }
}
