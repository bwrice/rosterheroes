<?php

namespace App\Http\Controllers;

use App\Aggregates\SquadAggregate;
use App\Domain\Models\HeroPostType;
use App\Domain\Models\SlotType;
use App\Events\HeroCreated;
use App\StorableEvents\SquadCreated;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Http\Resources\SquadResource;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\SquadRank;
use App\Domain\Models\MobileStorageRank;
use App\Domain\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HeroResource;
use App\Http\Resources\HeroClassResource;
use App\Http\Resources\HeroRaceResource;
use Illuminate\Support\Str;

class SquadController extends Controller
{
    public function store(Request $request)
    {
        //TODO authorize
        $request->validate([
            'name' => 'required|unique:squads|between:4,20|regex:/^[\w\-\s]+$/'
        ]);

        $uuid = Str::uuid();

        /** @var SquadAggregate $aggregate */
        $aggregate = SquadAggregate::retrieve($uuid);

        $aggregate->createSquad(
            auth()->user()->id,
            $request->name,
            SquadRank::getStarting()->id,
            MobileStorageRank::getStarting()->id,
            Province::getStarting()->id
        );

        $aggregate->persist();

        $aggregate->increaseEssence(Squad::STARTING_ESSENCE)
            ->increaseGold(Squad::STARTING_GOLD)
            ->increaseFavor(Squad::STARTING_FAVOR);

        $startingHeroPostTypes = HeroPostType::squadStarting();
        $startingHeroPostTypes->each(function (array $startingHeroPostType) use ($aggregate) {
            foreach (range(1, $startingHeroPostType['count']) as $count) {
                $aggregate->addHeroPost($startingHeroPostType['name']);
            }
        });

        $squad = Squad::uuid($uuid);

        $slotsNeededCount = $squad->mobileStorageRank->getBehavior()->getSlotsCount();
        $currentSlotsCount = $squad->slots()->count();
        $diff = $slotsNeededCount - $currentSlotsCount;

        $aggregate->addSlots(SlotType::UNIVERSAL, $diff);
        $aggregate->persist();

        return response()->json(new SquadResource($squad->fresh()), 201);
    }

    public function create()
    {
        return view('create-squad', [
            'squad' => null
        ]);
    }

    public function show(Request $request, $squadSlug, $any = null)
    {
        $squad = Squad::slugOrFail($squadSlug);
        return new SquadResource($squad->loadMissing([
            'heroPosts.hero.playerSpirit.game.homeTeam',
            'heroPosts.hero.playerSpirit.game.awayTeam',
            'heroPosts.hero.playerSpirit.player.team',
            'heroPosts.hero.playerSpirit.player.positions'
        ]));
    }
}
