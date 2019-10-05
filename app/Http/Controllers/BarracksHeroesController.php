<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Http\Resources\BarracksHeroResource;
use App\Http\Resources\HeroResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class BarracksHeroesController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $heroes = Hero::query()->amongSquad($squad)->get();
        $heroes->load([
            'heroRace',
            'heroClass',
            'combatPosition',
            'playerSpirit.player',
            'playerSpirit.game.homeTeam',
            'playerSpirit.game.awayTeam',
            'measurables.measurableType',
            'measurables.hasMeasurables',
            'slots.slotType',
            'slots.hasSlots',
            'slots.item.itemType.itemBase',
            'slots.item.material.materialType',
            'slots.item.itemClass',
            'slots.item.attacks.attackerPosition',
            'slots.item.attacks.targetPosition',
            'slots.item.attacks.targetPriority',
            'slots.item.attacks.damageType',
            'slots.item.enchantments.measurableBoosts.measurableType',
            'slots.item.enchantments.measurableBoosts.booster',
        ]);
        return BarracksHeroResource::collection($heroes);
    }
}
