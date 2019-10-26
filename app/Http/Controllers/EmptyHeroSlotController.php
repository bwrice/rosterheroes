<?php

namespace App\Http\Controllers;

use App\Domain\Actions\EmptyHeroSlotAction;
use App\Domain\Collections\SlotCollection;
use App\Domain\Collections\SlotTransactionCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Slot;
use App\Domain\Support\ItemTransactionGroup;
use App\Exceptions\SlottingException;
use App\Http\Resources\SlotTransactionResource;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmptyHeroSlotController extends Controller
{
    /**
     * @param $heroSlug
     * @param Request $request
     * @param EmptyHeroSlotAction $domainAction
     * @return ItemTransactionGroup
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($heroSlug, Request $request, EmptyHeroSlotAction $domainAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);

        $slot = Slot::findUuidOrFail($request->slot);
        try {
            /** @var ItemTransactionGroup $slotTransactionGroup */
            $slotTransactionGroup = DB::transaction(function () use ($hero, $slot, $domainAction) {
                return $domainAction->execute($slot, $hero);
            });

        } catch (SlottingException $exception) {

            throw ValidationException::withMessages([
                'equip' => $exception->getMessage()
            ]);
        }
        return $slotTransactionGroup;
    }
}
