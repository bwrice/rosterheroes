<?php


namespace App\Services;


use App\Domain\Models\Continent;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Domain\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class NPCService
{
    public function squadName()
    {
        $squadNames = $this->getSquads()->map(function ($squadArray) {
            return $squadArray['name'];
        });

        $existingSquadNames = Squad::query()->whereIn('name', $squadNames->toArray())->pluck('name');

        return $squadNames->diff($existingSquadNames)->random();
    }

    public function user()
    {
        return User::query()->where('email', '=', config('npc.user.email'))->firstOrFail();
    }

    protected function getBaseConfigKey()
    {
        return 'npc.' . app()->environment();
    }

    protected function getSquads()
    {
        $key = $this->getBaseConfigKey() . '.squads';
        return collect(config($key));
    }

    public function isNPC(Squad $squad)
    {
        return $squad->user_id === $this->user()->id;
    }

    public function heroName(Squad $squad)
    {
        $squadArray = $this->getSquads()->first(function ($squadArray) use ($squad) {
            return $squadArray['name'] === $squad->name;
        });

        // Find hero name not already in use and return it
        $heroNames = collect($squadArray['heroes']);
        $usedNames = Hero::query()->whereIn('name', $heroNames)->get()->pluck('name');
        $availableNames = $heroNames->diff($usedNames);
        if ($availableNames->isNotEmpty()) {
            return $availableNames->random();
        }

        /*
         * If we still dont have a hero name in use,
         * add characters to the end of the hero name until we have a unique name
         * or the length gets too long, then we fail
         */
        $name = $heroNames->random();
        $validName = false;
        while (! $validName && strlen($name) < 16) {
            $name .= Str::random(1);
            $hero = Hero::query()->where('name', '=', $name)->first();
            $validName = is_null($hero);
        }

        if (! $validName) {
            throw new \Exception("Couldn't find valid hero name for npc squad: " . $squad->name);
        }

        return $name;
    }

    public function heroSpirit(Hero $npcHero)
    {
        // Build initial query for spirits for current week with valid positions for hero's race
        $currentWeek = \App\Facades\CurrentWeek::get();
        $validPositionIDs = $npcHero->heroRace->positions()->pluck('id')->toArray();
        $query = PlayerSpirit::query()->forWeek($currentWeek)->whereHas('playerGameLog', function (Builder $builder) use ($validPositionIDs) {
            $builder->whereHas('player', function (Builder $builder) use ($validPositionIDs) {
                $builder->whereHas('positions', function (Builder $builder) use ($validPositionIDs) {
                    $builder->whereIn('id', $validPositionIDs);
                });
            });
        });

        // filter out spirits already in use by squad
        $spiritIDsInUseBySquad = $npcHero->squad->heroes()
            ->whereNotNull('player_spirit_id')
            ->pluck('player_spirit_id')->toArray();
        $query->whereNotIn('id', $spiritIDsInUseBySquad);

        // get spirit with a reasonable essence cost based on remaining spirit essence of the npc
        $availableSpiritEssence = $npcHero->squad->availableSpiritEssence();
        $heroesWithoutSpirits = $npcHero->squad->heroes()->whereNull('player_spirit_id')->count();
        $maxSpiritEssence = $heroesWithoutSpirits > 1 ? (int) ceil($availableSpiritEssence/$heroesWithoutSpirits) + 4000 : $availableSpiritEssence;
        $minSpiritEssence = min(7500, $maxSpiritEssence - 2000);
        $query->whereBetween('essence_cost', [$minSpiritEssence, $maxSpiritEssence]);

        // filter out spirits with minimum essence cost because they are likely not playing
        $flatCosts = Position::query()->get()->map(function (Position $position) {
            return $position->getDefaultEssenceCost();
        })->unique()->toArray();
        $query->whereNotIn('essence_cost', $flatCosts);

        return $query->inRandomOrder()->first();
    }
}
