<?php

namespace App\Http\Controllers;

use App\Domain\Actions\UnEquipItemFromHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;
use App\Http\Resources\ItemResource;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UnEquipHeroController extends Controller
{
    /**
     * @param $heroSlug
     * @param Request $request
     * @param UnEquipItemFromHeroAction $domainAction
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($heroSlug, Request $request, UnEquipItemFromHeroAction $domainAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);

        $item = Item::findUuidOrFail($request->item);
        try {
            $itemsMoved = $domainAction->execute($item, $hero);
            return ItemResource::collection($itemsMoved);

        } catch (ItemTransactionException $exception) {

            throw ValidationException::withMessages([
                'equip' => $exception->getMessage()
            ]);
        }
    }
}
