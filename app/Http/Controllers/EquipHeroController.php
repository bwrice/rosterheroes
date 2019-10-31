<?php

namespace App\Http\Controllers;

use App\Domain\Actions\EquipHeroSlotFromWagonAction;
use App\Domain\Actions\EquipWagonItemForHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;
use App\Http\Resources\HasItemsResource;
use App\Http\Resources\SlotTransactionResource;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EquipHeroController extends Controller
{
    /**
     * @param $heroSlug
     * @param Request $request
     * @param EquipWagonItemForHeroAction $domainAction
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($heroSlug, Request $request, EquipWagonItemForHeroAction $domainAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);

        $item = Item::findUuidOrFail($request->item);

        try {
            $hasItemsCollection = $domainAction->execute($item, $hero);

        } catch (ItemTransactionException $exception) {

            throw ValidationException::withMessages([
                'equip' => $exception->getMessage()
            ]);
        }

        return HasItemsResource::collection($hasItemsCollection);
    }
}
