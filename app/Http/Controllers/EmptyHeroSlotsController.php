<?php

namespace App\Http\Controllers;

use App\Domain\Actions\EmptyHeroSlotAction;
use App\Domain\Collections\SlotCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Slot;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EmptyHeroSlotsController extends Controller
{
    /**
     * @param $heroUuid
     * @param Request $request
     * @param EmptyHeroSlotAction $domainAction
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke($heroUuid, Request $request, EmptyHeroSlotAction $domainAction)
    {
        $hero = Hero::findUuidOrFail($heroUuid);
        $this->authorize(HeroPolicy::MANAGE, $hero);

        $slots = new SlotCollection();
        foreach($request->slots as $slotUuid) {
            $singleSlot = Slot::findUuidOrFail($slotUuid);
            $slots->push($singleSlot);
        };

        DB::transaction(function () use ($hero, $slots, $domainAction) {
            $slotTransactions = new Collection();
            $slots->each(function (Slot $slot) use ($hero, $slotTransactions, $domainAction) {
                $slotTransactions->merge($domainAction->execute($slot, $hero));
            });
        });
    }
}
