<?php

namespace App\Http\Controllers;

use App\Domain\Actions\SellItemBundleToShop;
use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Exceptions\SellItemToShopException;
use App\Http\Resources\ItemResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SellToShopController extends Controller
{
    public function __invoke($squadSlug, $shopSlug, Request $request, SellItemBundleToShop $sellItemBundleToShop)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);

        $shop = Shop::findSlugOrFail($shopSlug);
        $itemUuids = $request->post('items');

        /** @var ItemCollection $items */
        $items = Item::query()->whereIn('uuid', $itemUuids)->get();

        try {

            $itemsMoved = $sellItemBundleToShop->execute($items, $squad, $shop);
            return ItemResource::collection($itemsMoved->load(Item::resourceRelations()));

        } catch (SellItemToShopException $sellItemToShopException) {
            throw ValidationException::withMessages([
                'sell' => $sellItemToShopException->getMessage()
            ]);
        }
    }
}
