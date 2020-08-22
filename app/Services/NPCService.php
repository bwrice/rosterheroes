<?php


namespace App\Services;


use App\Domain\Models\Continent;
use App\Domain\Models\Hero;
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
        $squads = $this->getSquads()->random();
        return $squads['name'];
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

    public function questsToJoin(Squad $npc)
    {
        $npcLevel = $npc->level();

        // Get quests located in province npc can visit
        $validContinentIDs = Continent::query()->get()->filter(function (Continent $continent) use ($npcLevel) {
            return $continent->getBehavior()->getMinLevelRequirement() <= $npcLevel;
        })->pluck('id')->toArray();

        // If there are over 50 quests, we can safely grab 50 randomly and find some ideal quests to join
        $quests = Quest::query()->with('sideQuests.minions')->whereHas('province', function (Builder $builder) use ($validContinentIDs) {
            $builder->whereIn('continent_id', $validContinentIDs);
        })->inRandomOrder()->limit(50)->get();

        $idealDifficulty = ($npcLevel * 2) + 5;

        // Sort quests by average difficulty of side-quest relative to ideal difficulty for npc
        $orderedQuests = $quests->sortBy(function (Quest $quest) use ($npcLevel, $idealDifficulty) {
            $sideQuestDifficultyAvg = $quest->sideQuests->average(function (SideQuest $sideQuest) {
                return $sideQuest->difficulty();
            });
            return abs($idealDifficulty - $sideQuestDifficultyAvg);
        });

        $questsCount = $orderedQuests->count();
        $questsPerWeek = $npc->getQuestsPerWeek();

        // Get a rand int between quests-per-week and total quests available
        $amountOfQuestsToTake = $questsCount <= $questsPerWeek ? $questsCount : rand($questsPerWeek, $questsCount);

        $questsToJoin = $orderedQuests->take($amountOfQuestsToTake)->random($questsPerWeek);

        $sideQuestsPerQuest = $npc->getSideQuestsPerQuest();
        return $questsToJoin->map(function (Quest $quest) use ($sideQuestsPerQuest, $idealDifficulty) {

            $sideQuests = $quest->sideQuests->sortBy(function (SideQuest $sideQuest) use ($idealDifficulty) {
                return abs($idealDifficulty - $sideQuest->difficulty());
            });

            $sideQuestsCount = $sideQuests->count();

            // Get a rand int between side-quests-per-quest and total side-quests available
            $amountOfQuestsToTake = $sideQuestsCount <= $sideQuestsPerQuest ? $sideQuestsCount : rand($sideQuestsPerQuest, $sideQuestsCount);
            $sideQuestsToJoin = $sideQuests->take($amountOfQuestsToTake)->random($sideQuestsPerQuest);

            return [
                'quest' => $quest,
                'side_quests' =>  $sideQuestsToJoin->values()->all()
            ];
        });
    }
}
