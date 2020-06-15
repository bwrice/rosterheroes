<?php

namespace App\Domain\Models;

use App\Aggregates\SquadAggregate;
use App\Domain\Models\Chest;
use App\Domain\Actions\CreateCampaignAction;
use App\Domain\Collections\ItemCollection;
use App\Domain\Collections\SquadCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Interfaces\TravelsBorders;
use App\Domain\QueryBuilders\CampaignQueryBuilder;
use App\Domain\QueryBuilders\SquadQueryBuilder;
use App\Domain\Services\Travel\SquadBorderTravelCostExemption;
use App\Exceptions\CampaignExistsException;
use App\Exceptions\NotBorderedByException;
use App\Exceptions\QuestRequiredException;
use App\Exceptions\WeekLockedException;
use App\Domain\Collections\HeroCollection;
use App\Domain\Collections\HeroPostCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Collections\SlotCollection;
use App\Domain\Traits\HasNameSlug;
use App\Facades\SquadService;
use App\Http\Resources\MobileStorageResource;
use App\Http\Resources\SquadResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\JsonResource;
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
 * @property int $damage_dealt
 * @property int $minion_damage_dealt
 * @property int $titan_damage_dealt
 * @property int $side_quest_damage_dealt
 * @property int $quest_damage_dealt
 * @property int $damage_taken
 * @property int $minion_damage_taken
 * @property int $titan_damage_taken
 * @property int $side_quest_damage_taken
 * @property int $quest_damage_taken
 * @property int $attacks_blocked
 * @property int $minion_kills
 * @property int $titan_kills
 * @property int $combat_kills
 * @property int $side_quest_kills
 * @property int $quest_kills
 * @property int $side_quest_deaths
 * @property int $minion_deaths
 * @property int $titan_deaths
 * @property int $combat_deaths
 * @property int $side_quest_victories
 * @property int $side_quest_defeats
 *
 * @property User $user
 * @property Province $province
 * @property MobileStorageRank $mobileStorageRank
 *
 * @property HeroCollection $heroes
 * @property ItemCollection $items
 * @property HeroPostCollection $heroPosts
 * @property Collection $chests
 * @property Collection $stashes
 * @property Collection $campaigns
 *
 * @method static SquadQueryBuilder query()
 */
class Squad extends EventSourcedModel implements HasItems
{
    use HasNameSlug;

    const RELATION_MORPH_MAP_KEY = 'squads';

    const STARTING_GOLD = 500;
    const STARTING_EXPERIENCE = 1000;
    const STARTING_FAVOR = 100;
    const STARTING_ESSENCE = 30000;
    const QUESTS_PER_WEEK = 3;
    const SIDE_QUESTS_PER_QUEST = 5;

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

    public const STARTING_SPELLS = [
        'Muscle',
        'Boldness',
        'Quickness',
        'Alertness',
        'Competence',
        'Sense',
        'Well-Being',
        'Fettle',
        'Vigor',
        'Resolve',
        'Tolerance',
        'Firmness',
        'Push',
        'Relaxation',
        'Decency',
        'Leverage',
        'Aggression',
    ];

    protected $guarded = [];

    public function newEloquentBuilder($query)
    {
        return new SquadQueryBuilder($query);
    }

    public function newCollection(array $models = [])
    {
        return new SquadCollection($models);
    }

    public static function getMobileStorageResourceRelations()
    {
        return [
            'items.itemType.itemBase',
            'items.material.materialType',
            'items.itemClass',
            'items.attacks.attackerPosition',
            'items.attacks.targetPosition',
            'items.attacks.targetPriority',
            'items.attacks.damageType',
            'items.enchantments.measurableBoosts.measurableType',
            'items.enchantments.measurableBoosts.booster',
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

    public static function getStartingHeroesCount()
    {
        return collect(self::STARTING_HERO_POST_TYPES)->sum();
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

    public function heroes()
    {
        return $this->hasMany(Hero::class);
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

    public function items()
    {
        return $this->morphMany(Item::class, 'has_items');
    }

    public function spells()
    {
        return $this->belongsToMany(Spell::class)->withTimestamps();
    }

    public function stashes()
    {
        return $this->hasMany(Stash::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chests()
    {
        return $this->hasMany(Chest::class);
    }

    /**
     * @return Stash
     */
    public function getLocalStash()
    {
        return $this->stashes()->firstOrCreate([
            'province_id' => $this->province_id
        ], [
            'uuid' => Str::uuid(),
        ]);
    }

    public function residences()
    {
        return $this->hasMany(Residence::class);
    }

    /**
     * @return HasMany|CampaignQueryBuilder
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * @return Residence|null
     */
    public function getLocalResidence(): ?Residence
    {
        return $this->residences()->where('province_id', '=', $this->province_id)->first();
    }

    /**
     * @return int
     */
    public function availableSpiritEssence()
    {
        return $this->spirit_essence - $this->heroes()->with('playerSpirit')->get()->totalEssenceCost();
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
     * @param array $relations
     * @return Campaign|null
     */
    public function getCurrentCampaign($relations = [])
    {
        return $this->campaigns()->forCurrentWeek()->with($relations)->first();
    }

    /**
     * @return Campaign|null
     */
    public function getThisWeeksCampaign()
    {
        return $this->campaigns()->forCurrentWeek()->first();
    }


    public function getQuestsPerWeekAllowed(): int
    {
        return self::QUESTS_PER_WEEK;
    }

    /**
     * @return bool
     */
    public function inCreationState()
    {
        return $this->heroes()->count() < self::getStartingHeroesCount();
    }

    public function getHeroRaceAvailability()
    {
        $availableHeroPosts = $this->getEmptyHeroPosts();
        $heroRaces = collect();
        $availableHeroPosts->each(function(HeroPost $heroPost) use ($heroRaces) {
            return $heroRaces->push($heroPost->getHeroRaces());
        });

        return $heroRaces->flatten()->unique();
    }

    public function getEmptyHeroPosts()
    {
        return $this->buildHeroPosts()->postEmpty();
    }

    public function buildHeroPosts()
    {
        return $this->heroPosts->fillHeroes($this->heroes);
    }

    public function getHeroClassAvailability()
    {
        if ($this->inCreationState()) {

            // Filter any required starting classes that we currently don't have a hero for
            $requiredClasses = HeroClass::requiredStarting()->get();
            $missingClasses = $requiredClasses->filter(function (HeroClass $heroClass) {
                $heroWithClass = $this->heroes->filterByClass($heroClass)->first();
                return is_null($heroWithClass);
            });

            /*
             * If we don't have more empty hero posts than missing classes, the
             * missing hero classes ARE the squad's available classes
             */
            $emptyHeroPosts = $this->getEmptyHeroPosts();
            if ($emptyHeroPosts->count() <= $missingClasses->count()) {
                return $missingClasses;
            }
        }

        return HeroClass::all();
    }

    public function getAvailableGold(): int
    {
        return $this->gold;
    }

    public function increaseGold(int $amount)
    {
        $this->gold += $amount;
        $this->save();
    }

    public function decreaseGold(int $amount)
    {
        $this->gold -= $amount;
        $this->save();
    }

    public function getBackupHasItems(): ?HasItems
    {
        return $this->getLocalResidence() ?: $this->getLocalStash();
    }

    public function hasRoomForItem(Item $item): bool
    {
        return $this->getOverCapacityAmount($item) <= 0;
    }

    public function itemsToMoveForNewItem(Item $item): ItemCollection
    {
        $overCapacityAmount = $this->getOverCapacityAmount($item);

        $itemsToMove = new ItemCollection();

        $this->items()
            ->with(['material', 'itemType'])
            ->orderBy('updated_at')
            ->chunk(100, function (ItemCollection $items)  use ($itemsToMove, &$overCapacityAmount){

                // No need to run anymore logic if we already have enough items to move
                if ($overCapacityAmount > 0) {

                    $sortedByWeightItems = $items->sortByWeight(true);

                    while($overCapacityAmount > 0) {
                        /** @var Item $singleItemToMove */
                        $singleItemToMove = $sortedByWeightItems->shift();
                        $itemsToMove->push($singleItemToMove);
                        $overCapacityAmount -= $singleItemToMove->weight();
                    }
                }
            });

        return $itemsToMove;
    }

    protected function getOverCapacityAmount(Item $item): int
    {
        $maxWeightCapacity = $this->mobileStorageRank->getBehavior()->getWeightCapacity();
        $currentCapacityUsed = $this->getMobileStorageCapacityUsed();
        return ($currentCapacityUsed + $item->weight()) - $maxWeightCapacity;
    }

    public function getMorphType(): string
    {
        return static::RELATION_MORPH_MAP_KEY;
    }

    public function getMorphID(): int
    {
        return $this->id;
    }

    public function getQuestsPerWeek(): int
    {
        return static::QUESTS_PER_WEEK;
    }

    public function getSideQuestsPerQuest(): int
    {
        return static::SIDE_QUESTS_PER_QUEST;
    }

    public function combatReady()
    {
        return SquadService::combatReady($this);
    }

    /**
     * @return int
     */
    public function getMobileStorageCapacityUsed()
    {
        $this->loadMissing([
            'items.itemType.itemBase',
            'items.material.materialType'
        ]);
        $currentCapacity = $this->items->sumOfWeight();
        return $currentCapacity;
    }

    public function getTransactionIdentification(): array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->getMorphType()
        ];
    }

    public function level()
    {
        return (int) floor(sqrt($this->experience/1000));
    }

    /**
     * @param int $level
     * @return int
     */
    public static function totalExperienceNeededForLevel(int $level)
    {
        return ($level ** 2) * 1000;
    }

    public function experienceOverLevel()
    {
        $currentLevel = $this->level();
        return $this->experience - self::totalExperienceNeededForLevel($currentLevel);
    }

    public function experienceUntilNextLevel()
    {
        $nextLevel = $this->level() + 1;
        $nextLevelExperience = self::totalExperienceNeededForLevel($nextLevel);
        return (int) $nextLevelExperience - $this->experience;
    }
}
