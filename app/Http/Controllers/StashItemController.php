<?php

namespace App\Http\Controllers;

use App\Domain\Actions\StashItem;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Http\Resources\ItemResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class StashItemController extends Controller
{
    public function __invoke($squadSlug, StashItem $stashItemFromMobileStorage, Request $request)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $item = Item::findUuidOrFail($request->item);

        $stashedItem = $stashItemFromMobileStorage->execute($item, $squad);
        return new ItemResource($stashedItem->load(Item::resourceRelations()));
    }
}
