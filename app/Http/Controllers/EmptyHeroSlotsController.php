<?php

namespace App\Http\Controllers;

use App\Domain\Actions\EmptyHeroSlotAction;
use App\Domain\Collections\SlotCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Slot;
use App\Http\Resources\SlotTransactionResource;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EmptyHeroSlotsController extends Controller
{
    /**
     * @param $heroSlug
     * @param Request $request
     * @param EmptyHeroSlotAction $domainAction
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($heroSlug, Request $request, EmptyHeroSlotAction $domainAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);

        $slots = new SlotCollection();
        foreach($request->slots as $slotUuid) {
            $singleSlot = Slot::findUuidOrFail($slotUuid);
            $slots->push($singleSlot);
        };

        /** @var Collection $slotTransactions */
        $slotTransactions = DB::transaction(function () use ($hero, $slots, $domainAction) {
            $slotTransactions = new Collection();
            $slots->each(function (Slot $slot) use ($hero, &$slotTransactions, $domainAction) {
                $slotTransactions = $slotTransactions->merge($domainAction->execute($slot, $hero));
            });
            return $slotTransactions;
        });

        return SlotTransactionResource::collection($slotTransactions);
    }
}
