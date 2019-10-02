<?php

namespace App\Http\Controllers;

use App\Domain\Actions\EquipHeroSlotFromWagonAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use App\Domain\Support\SlotTransaction;
use App\Exceptions\SlottingException;
use App\Http\Resources\SlotTransactionResource;
use App\Policies\HeroPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EquipHeroController extends Controller
{
    public function __invoke($heroSlug, Request $request, EquipHeroSlotFromWagonAction $domainAction)
    {
        $hero = Hero::findSlugOrFail($heroSlug);
        $this->authorize(HeroPolicy::MANAGE, $hero);

        $slot = Slot::findUuidOrFail($request->slot);
        $item = Item::findUuidOrFail($request->item);

        try {
            $slotTransactions = $domainAction->execute($hero, $slot, $item);

        } catch (SlottingException $exception) {

            throw ValidationException::withMessages([
                'equip' => $exception->getMessage()
            ]);
        }

        $slotTransactions->refresh();
        return SlotTransactionResource::collection($slotTransactions);
    }
}
