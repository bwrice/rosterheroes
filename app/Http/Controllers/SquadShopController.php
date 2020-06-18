<?php

namespace App\Http\Controllers;

use App\Domain\Models\Shop;
use App\Domain\Models\Squad;
use App\Http\Resources\ShopResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class SquadShopController extends Controller
{
    public function show($squadSlug, $shopSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $shop = Shop::findSlugOrFail($shopSlug);

        $this->authorize(SquadPolicy::VISIT_SHOP, [
            $squad,
            $shop
        ]);

        return new ShopResource($shop->load(Shop::getResourceRelations()));
    }
}
