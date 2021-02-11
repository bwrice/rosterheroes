<?php

namespace App\Http\Controllers;

use App\Domain\Actions\EquipItemForHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;
use App\Http\Resources\ItemResource;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EquipHeroController extends Controller
{
    /**
     * @param $heroSlug
     * @param Request $request
     * @param EquipItemForHeroAction $domainAction
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($heroSlug, Request $request, EquipItemForHeroAction $domainAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);

        $item = Item::findUuidOrFail($request->item);

        try {
            $itemsMoved = $domainAction->execute($item, $hero);
            $itemsMoved->load(Item::resourceRelations());

        } catch (ItemTransactionException $exception) {

            throw ValidationException::withMessages([
                'equip' => $exception->getMessage()
            ]);
        }

        return ItemResource::collection($itemsMoved);
    }
}
