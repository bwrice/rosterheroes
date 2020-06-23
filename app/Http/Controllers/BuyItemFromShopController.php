<?php

namespace App\Http\Controllers;

use App\Domain\Actions\BuyItemFromShop;
use App\Domain\Models\Item;
use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Exceptions\BuyItemFromShopException;
use App\Http\Resources\ItemResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BuyItemFromShopController extends Controller
{
    /**
     * @param $squadSlug
     * @param $shopSlug
     * @param Request $request
     * @param BuyItemFromShop $buyItemFromShop
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($squadSlug, $shopSlug, Request $request, BuyItemFromShop $buyItemFromShop)
    {
        $squad = Squad::findSlugOrFail($squadSlug);

        $this->authorize(SquadPolicy::MANAGE, $squad);

        $shop = Shop::findSlugOrFail($shopSlug);
        $item = Item::findUuidOrFail($request->post('item'));

        try {
            $items = $buyItemFromShop->execute($item, $shop, $squad);
            return ItemResource::collection($items);
        } catch (BuyItemFromShopException $exception) {

            throw ValidationException::withMessages([
                'buy' => $exception->getMessage()
            ]);
        }
    }
}
