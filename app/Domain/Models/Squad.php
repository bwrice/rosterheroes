<?php

namespace App\Domain\Models;

use App\Aggregates\SquadAggregate;
use App\Domain\Actions\CreateCampaignAction;
use App\Domain\Interfaces\TravelsBorders;
use App\Domain\Services\Travel\SquadBorderTravelCostExemption;
use App\Exceptions\CampaignExistsException;
use App\Exceptions\NotBorderedByException;
use App\Exceptions\QuestRequiredException;
use App\Exceptions\WeekLockedException;
use App\Domain\Collections\HeroCollection;
use App\Domain\Collections\HeroPostCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Collections\SlotCollection;
use App\Domain\Traits\HasSlug;
use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Squad
 * @package App
 *
 * @property int $id
 * @property string $uuid
 * @property string $slug
 * @property int $user_id
 * @property string $name
 * @property int $squad_rank_id
 * @property int $province_id
 * @property int $spirit_essence
 * @property int $experience
 * @property int $gold
 * @property int $favor
 * @property int $hero_posts
 *
 * @property User $user
 * @property Province $province
 * @property MobileStorageRank $mobileStorageRank
 * @property \App\Domain\Collections\SlotCollection $slots
 * @property Campaign|null $currentCampaign
 *
 * @property HeroPostCollection $heroPosts
 */
class Squad extends EventSourcedModel implements HasSlots, TravelsBorders
{
    use HasSlug;

    const RELATION_MORPH_MAP_KEY = 'squads';

    const STARTING_GOLD = 500;
    const STARTING_EXPERIENCE = 1000;
    const STARTING_FAVOR = 100;
    const STARTING_ESSENCE = 30000;
    const QUESTS_PER_WEEK = 3;
    const SKIRMISHES_PER_QUEST = 5;

    const STARTING_HERO_POSTS = [
        HeroRace::HUMAN => 1,
        HeroRace::DWARF => 1,
        HeroRace::ELF => 1,
        HeroRace::ORC => 1
    ];

    public const STARTING_HERO_POST_TYPES = [
        HeroPostType::HUMAN => 1,
        HeroPostType::ELF => 1,
        HeroPostType::DWARF => 1,
        HeroPostType::ORC => 1
    ];

    protected $guarded = [];

    public static function getMobileStorageResourceRelations()
    {
        return [
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
            'mobileStorageRank'
        ];
    }

    /**
     * @return SquadAggregate
     */
    public function getAggregate()
    {
        /** @var SquadAggregate $aggregate */
        $aggregate = SquadAggregate::retrieve($this->uuid);
        return $aggregate;
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public static function getStartingHeroesCount()
    {
        return collect(self::STARTING_HERO_POST_TYPES)->sum();
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
                    'uuid' => Str::uuid(),
                    'slot_type_id' => $slotType->id
                ]);
            }
        }
    }

    public function addStartingHeroPosts()
    {
        collect(self::STARTING_HERO_POST_TYPES)->each(function ($count, $postTypeName) {

            $heroPostType = HeroPostType::where('name', '=', $postTypeName)->first();

            foreach(range(1, $count) as $heroPostTypeCount) {
                $this->addHeroPost($heroPostType);
            }
        });
    }

    public function addHeroPost(HeroPostType $heroPostType)
    {
        $this->heroPosts()->create([
            'hero_post_type_id' => $heroPostType->id
        ]);
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
     * @param array $relations
     * @return HeroCollection
     */
    public function getHeroes($relations = ['hero'])
    {
        $heroes = new HeroCollection();
        $this->heroPosts->load($relations)->each(function (HeroPost $heroPost) use ($heroes) {
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

    /**
     * @return Stash
     */
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
     * @return \App\Domain\Traits\\App\Domain\Interfaces\HasSlots
     */
    public function getBackupHasSlots(): ?HasSlots
    {
        return $this->getLocalStash();
    }

    /**
     * @param array $with
     * @return \App\Domain\Traits\\App\Domain\Interfaces\HasSlots
     */
    public function getFresh($with = []): HasSlots
    {
        return $this->fresh($with);
    }

    /**
     * @return \App\Domain\Collections\SlotCollection
     */
    public function getSlots(): SlotCollection
    {
        return $this->slots;
    }

    /**
     * @return int
     */
    public function availableSpiritEssence()
    {
        return $this->spirit_essence - $this->getHeroes()->totalEssenceCost();
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
     * @return Campaign|null
     */
    public function getThisWeeksCampaign()
    {
        /** @var Campaign $campaign */
        $campaign = Campaign::forSquadWeek($this, Week::current())->first();
        return $campaign;
    }


    public function getQuestsPerWeekAllowed(): int
    {
        return self::QUESTS_PER_WEEK;
    }

//    public function addSkirmish(Skirmish $skirmish)
//    {
//        if(! $this->joinedQuestForCurrentWeek($skirmish->quest)) {
//            throw new QuestRequiredException($skirmish->quest);
//        }
//    }

    /**
     * @param Quest $quest
     * @return bool
     */
    public function joinedQuestForCurrentWeek(Quest $quest)
    {
        $campaign = $this->getThisWeeksCampaign();
        return $campaign ? in_array($quest->id, $campaign->quests->pluck('id')->toArray()) : false;
    }

    /**
     * @return Campaign
     * @throws CampaignExistsException
     * @throws WeekLockedException
     */
    public function createCampaign(): Campaign
    {
        $week = Week::current();
        $createCampaignAction = new CreateCampaignAction($this, $week, $this->province->continent);
        return $createCampaignAction();
    }

    /**
     * @return bool
     */
    public function inCreationState()
    {
        return $this->getHeroes()->count() < self::getStartingHeroesCount();
    }

    public function getHeroRaceAvailability()
    {
        $availableHeroPosts = $this->getHeroPostAvailability();
        $heroRaces = collect();
        $availableHeroPosts->each(function(HeroPost $heroPost) use ($heroRaces) {
            return $heroRaces->push($heroPost->getHeroRaces());
        });

        return $heroRaces->flatten()->unique();
    }

    public function getHeroPostAvailability()
    {
        return $this->heroPosts->postFilled(false);
    }

    public function getHeroClassAvailability()
    {
        if ($this->inCreationState()) {

            $emptyHeroPosts = $this->heroPosts->postFilled(false);
            $requiredClasses = HeroClass::requiredStarting()->get();
            $heroes = $this->getHeroes();

            $missingClasses = $requiredClasses->filter(function (HeroClass $heroClass) use ($heroes) {
                $heroWithClass = $heroes->filterByClass($heroClass)->first();
                return $heroWithClass === null;
            });

            if ($missingClasses->count() >= $emptyHeroPosts->count()) {
                return $missingClasses;
            }
        }

        return HeroClass::all();
    }

    public function getAvailableGold(): int
    {
        return $this->gold;
    }

    public function hasBorderTravelCostExemption(Province $border): bool
    {
        /** @var SquadBorderTravelCostExemption $costExemption */
        $costExemption = app(SquadBorderTravelCostExemption::class);
        return $costExemption->isExempt($this, $border);
    }

    public function getCurrentLocation(): Province
    {
        return $this->province;
    }

    public function increaseGold(int $amount)
    {
        $this->getAggregate()->increaseGold($amount);
    }

    public function decreaseGold(int $amount)
    {
        $this->getAggregate()
            ->decreaseGold($amount)
            ->persist();
    }

    public function updateLocation(Province $newLocation)
    {
        $this->getAggregate()
            ->updateLocation($this->province_id, $newLocation->id)
            ->persist();
    }

    public function getUniqueIdentifier(): string
    {
        return (string) $this->uuid;
    }
}
