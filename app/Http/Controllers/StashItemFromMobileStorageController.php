<?php

namespace App\Http\Controllers;

use App\Domain\Actions\StashItemFromMobileStorage;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Http\Resources\ItemResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class StashItemFromMobileStorageController extends Controller
{
    public function __invoke($squadSlug, StashItemFromMobileStorage $stashItemFromMobileStorage, Request $request)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $item = Item::findUuidOrFail($request->item);

        $stashedItem = $stashItemFromMobileStorage->execute($item, $squad);
        return new ItemResource($stashedItem);
    }
}
