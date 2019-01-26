<?php

namespace App;

use App\Campaigns\Quests\Quest;
use App\Events\SquadFavorIncreased;
use App\Events\SquadCreated;
use App\Events\SquadCreationRequested;
use App\Events\SquadGoldIncreased;
use App\Events\SquadHeroPostAdded;
use App\Events\SquadSalaryIncreased;
use App\Exceptions\InvalidContinentException;
use App\Exceptions\InvalidProvinceException;
use App\Exceptions\MaxQuestsException;
use App\Exceptions\NotBorderedByException;
use App\Exceptions\QuestCompletedException;
use App\Exceptions\QuestRequiredException;
use App\Exceptions\WeekLockedException;
use App\Heroes\HeroCollection;
use App\Heroes\HeroPosts\HeroPost;
use App\Heroes\HeroPosts\HeroPostCollection;
use App\Slots\HasSlots;
use App\Slots\Slot;
use App\Slots\SlotCollection;
use App\Squads\MobileStorage\MobileStorageRank;
use App\Weeks\Week;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class Squad
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $name
 * @property int $squad_rank_id
 * @property int $province_id
 * @property int $salary
 * @property int $experience
 * @property int $gold
 * @property int $favor
 * @property int $hero_posts
 *
 * @property User $user
 * @property Province $province
 * @property MobileStorageRank $mobileStorageRank
 * @property SlotCollection $slots
 * @property Campaign|null $currentCampaign
 *
 * @property HeroPostCollection $heroPosts
 */
class Squad extends EventSourcedModel implements HasSlots
{
    const STARTING_GOLD = 500;
    const STARTING_FAVOR = 100;
    const STARTING_SALARY = 30000;
    const QUESTS_PER_WEEK = 3;
    const SKIRMISHES_PER_QUEST = 5;

    const STARTING_HERO_POSTS = [
        HeroRace::HUMAN => 1,
        HeroRace::DWARF => 1,
        HeroRace::ELF => 1,
        HeroRace::ORC => 1
    ];

    protected $guarded = [];

    /**
     * @param int $userID
     * @param string $name
     * @param array $heroesData
     * @return Squad
     * @throws \Exception
     *
     * Creates a new Squad with Heroes and triggers associated events
     */
    public static function generate(int $userID, string $name, array $heroesData)
    {
        /** @var Squad $squad */
        $squad = self::createWithAttributes([
            'user_id' => $userID,
            'name' => $name,
            'squad_rank_id' => SquadRank::getStarting()->id,
            'mobile_storage_rank_id' => MobileStorageRank::getStarting()->id,
            'province_id' => Province::getStarting()->id
        ]);

        // Hooked into for adding wagon
        event(new SquadCreated($squad));

        // Give starting salary, gold and favor to new squad
        $squad->increaseSalary(self::STARTING_SALARY);
        $squad->increaseGold(self::STARTING_GOLD);
        $squad->increaseFavor(self::STARTING_FAVOR);

        foreach($heroesData as $hero) {

            Hero::generate($squad, $hero['name'], $hero['class'], $hero['race']);
        }

        return $squad->load('heroes', 'province');
    }

    public function increaseSalary(int $amount)
    {
        event(new SquadSalaryIncreased($this->uuid, $amount));
    }

    public function increaseGold(int $amount)
    {
        event(new SquadGoldIncreased($this->uuid, $amount));
    }

    public function increaseFavor(int $amount)
    {
        event(new SquadFavorIncreased($this->uuid, $amount));
    }

    public function addSlots()
    {
        $slotsNeededCount = $this->mobileStorageRank->getBehavior()->getSlotsCount();
        $currentSlotsCount = $this->slots()->count();
        $diff = $slotsNeededCount - $currentSlotsCount;

        if($diff > 0) {
            /** @var SlotType $slotType */
            $slotType = SlotType::where('name', '=', SlotType::UNIVERSAL)->first();
            for($i = 1; $i <= $diff; $i++) {
                $this->slots()->create([
                    'slot_type_id' => $slotType->id
                ]);
            }
        }
    }

    /**
     * @return Collection
     */
    public function getStartingHeroPostRaces()
    {
        return HeroRace::query()->whereIn('name', array_keys(self::STARTING_HERO_POSTS))->get();
    }

    public function addStartingHeroPosts()
    {
        $this->getStartingHeroPostRaces()->each(function(HeroRace $heroRace) {
            $this->addHeroPost($heroRace);
        });
    }

    public function addHeroPost(HeroRace $heroRace)
    {
        $this->heroPosts()->create([
            'hero_race_id' => $heroRace->id
        ]);
    }

    /**
     * @param array $attributes
     * @return Squad|null
     * @throws \Exception
     */
    public static function createWithAttributes(array $attributes)
    {
        $uuid = (string) Uuid::uuid4();

        $attributes['uuid'] = $uuid;

        event(new SquadCreationRequested($attributes));

        return self::uuid($uuid);
    }

    public function mobileStorageRank()
    {
        return $this->belongsTo(MobileStorageRank::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function heroPosts()
    {
        return $this->hasMany(HeroPost::class);
    }

    /**
     * @return HeroCollection
     */
    public function getHeroes()
    {
        $heroes = new HeroCollection();
        $this->heroPosts->each(function (HeroPost $heroPost) use ($heroes) {
            if ($heroPost->hero) {
                $heroes->push($heroPost->hero);
            }
        });
        return $heroes;
    }

    public function slots()
    {
        return $this->morphMany(Slot::class, 'has_slots');
    }

    public function stashes()
    {
        return $this->hasMany(Stash::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getLocalStash()
    {
        return $this->stashes()->firstOrCreate([
            'province_id' => $this->province_id
        ]);
    }

    public function storeHouses()
    {
        return $this->hasMany(StoreHouse::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function currentCampaign()
    {
        return $this->hasOne(Campaign::class)->where('week_id', '=', Week::current()->id);
    }

    /**
     * @return StoreHouse|null
     */
    public function getLocalStoreHouse(): ?StoreHouse
    {
        return $this->storeHouses()->where('province_id', '=', $this->province_id)->first();
    }

    /**
     * @param int $count
     * @param array $slotTypeIDs
     * @return SlotCollection
     */
    public function getEmptySlots(int $count, array $slotTypeIDs = []): SlotCollection
    {
        return $this->slots->slotEmpty()->withSlotTypes($slotTypeIDs);
    }

    /**
     * @return HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return $this->getLocalStash();
    }

    /**
     * @param array $with
     * @return HasSlots
     */
    public function getFresh($with = []): HasSlots
    {
        return $this->fresh($with);
    }

    /**
     * @return SlotCollection
     */
    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }

    /**
     * @return int
     */
    public function availableSalary()
    {
        return $this->salary - $this->getHeroes()->totalSalary();
    }

    public function borderTravel(Province $border)
    {
        if(! $this->province->isBorderedBy($border)) {
            throw (new NotBorderedByException())->setProvinces($this->province, $border);
        }
        $this->province_id = $border->id;
        $this->save();
    }

    /**
     * @param Continent $continent
     *
     * @return Campaign
     */
    public function getContinentsCampaign(Continent $continent)
    {
        if ($this->currentCampaign) {
            if ($this->currentCampaign != $continent->id) {
                throw new InvalidContinentException($this->currentCampaign->continent, $continent);
            } else {
                $this->currentCampaign;
            }
        } else {
            return $this->campaigns()->create([
                'week_id' => Week::current()->id,
                'continent_id' => $continent->id
            ]);
        }
    }

    /**
     * @return Campaign|null
     */
    public function getThisWeeksCampaign()
    {
        /** @var Campaign $campaign */
        $campaign = Campaign::squadThisWeek($this->id)->first();
        return $campaign;
    }


    public function getQuestsPerWeekAllowed(): int
    {
        return self::QUESTS_PER_WEEK;
    }

    public function addSkirmish(Skirmish $skirmish)
    {
        if(! $this->joinedQuestForCurrentWeek($skirmish->quest)) {
            throw new QuestRequiredException($skirmish->quest);
        }
    }

    /**
     * @param Quest $quest
     * @return bool
     */
    public function joinedQuestForCurrentWeek(Quest $quest)
    {
        $campaign = $this->getThisWeeksCampaign();
        return $campaign ? in_array($quest->id, $campaign->quests->pluck('id')->toArray()) : false;
    }
}
