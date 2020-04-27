<?php

namespace App\Http\Controllers;

use App\Domain\Actions\MobileStoreItemForSquad;
use App\Domain\Models\Item;
use App\Domain\Models\Squad;
use App\Http\Resources\ItemResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class MobileStoreItemForSquadController extends Controller
{
    public function __invoke($squadSlug, MobileStoreItemForSquad $mobileStoreItemForSquad, Request $request)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $item = Item::findUuidOrFail($request->item);
        $itemsMoved = $mobileStoreItemForSquad->execute($item, $squad);
        return ItemResource::collection($itemsMoved);
    }
}
