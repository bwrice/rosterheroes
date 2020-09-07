<?php


namespace App\Factories\Models;


use App\Domain\Models\CombatPosition;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\ItemBase;
use App\Domain\Models\League;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\Squad;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HeroFactory
{
    /** @var int */
    protected $squadID;

    /** @var int */
    protected $heroClassID;

    /** @var int */
    protected $heroRankID;

    /** @var int */
    protected $heroRaceID;

    /** @var int */
    protected $combatPositionID;

    /** @var SquadFactory */
    protected $squadFactory;

    /** @var Collection */
    protected $measurableFactories;

    protected $withItems = false;

    /** @var Collection */
    protected $itemFactories;

    /** @var PlayerSpiritFactory|null */
    protected $playerSpiritFactory;

    protected function __construct()
    {
        $this->squadFactory = SquadFactory::new();
        $this->measurableFactories = collect();
    }

    public static function new(): self
    {
        return new self();
    }

    /**
     * @param array $extra
     * @return Hero
     * @throws \Exception
     */
    public function create(array $extra = []): Hero
    {
        /** @var Hero $hero */
        $hero = Hero::query()->create(array_merge(
            [
                'squad_id' => $this->squadID ?: $this->squadFactory->create()->id,
                'name' => 'TestHero_' . rand(1,999999999),
                'uuid' => (string) Str::uuid(),
                'hero_class_id' => $this->heroClassID ?: $this->getDefaultHeroClassID(),
                'hero_race_id' => $this->heroRaceID ?: $this->getDefaultHeroRaceID(),
                'hero_rank_id' => $this->heroRankID ?: $this->getDefaultHeroRank(),
                'combat_position_id' => $this->combatPositionID ?: $this->getDefaultCombatPositionID()
            ],
            $extra
        ));

        $this->measurableFactories->each(function (MeasurableFactory $measurableFactory) use ($hero) {
            $measurableFactory->forHero($hero)->create();
        });

        if ($this->withItems) {
            if ($this->itemFactories) {
                $itemFactories = $this->itemFactories;
            } else {
                $itemFactories = $this->getDefaultItemFactories($hero);
            }
            $itemFactories->each(function (ItemFactory $factory) use ($hero) {
                return $factory->forHero($hero)->create();
            });
        }

        if ($this->playerSpiritFactory) {
            $playerSpirit = $this->playerSpiritFactory->create();
            $hero->player_spirit_id = $playerSpirit->id;
            $hero->save();
        }
        return $hero->fresh();
    }

    public function heroClass(string $heroClassName)
    {
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->where('name', '=', $heroClassName)->first();

        $clone = clone $this;
        $clone->heroClassID = $heroClass->id;
        return $clone;
    }

    protected function getDefaultHeroClassID()
    {
        return HeroClass::query()->inRandomOrder()->first()->id;
    }

    public function heroRace(string $heroRaceName)
    {
        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name', '=', $heroRaceName)->first();

        $clone = clone $this;
        $clone->heroRaceID = $heroRace->id;
        return $clone;
    }

    protected function getDefaultHeroRaceID()
    {
        return HeroRace::query()->inRandomOrder()->first()->id;
    }

    public function heroRank(string $heroRankName)
    {
        /** @var HeroRank $heroRank */
        $heroRank = HeroRank::query()->where('name', '=', $heroRankName)->first();

        $clone = clone $this;
        $clone->heroRankID = $heroRank->id;
        return $clone;
    }

    protected function getDefaultHeroRank()
    {
        return HeroRank::private()->id;
    }

    public function combatPosition(string $combatPositionName)
    {
        /** @var CombatPosition $combatPosition */
        $combatPosition = CombatPosition::query()->where('name', '=', $combatPositionName)->first();

        $clone = clone $this;
        $clone->combatPositionID = $combatPosition->id;
        return $clone;
    }

    protected function getDefaultCombatPositionID()
    {
        return CombatPosition::query()->inRandomOrder()->first()->id;
    }

    public function forSquad(Squad $squad)
    {
        $clone = clone $this;
        $clone->squadID = $squad->id;
        return $clone;
    }

    public function withSquadID(int $squadID)
    {
        $clone = clone $this;
        $clone->squadID = $squadID;
        return $clone;
    }

    public function squad(SquadFactory $squadFactory)
    {
        $clone = clone $this;
        $clone->squadFactory = $squadFactory;
        return $clone;
    }

    public function withMeasurables(Collection $measurableFactories = null)
    {
        $measurableFactories = $measurableFactories ?: collect();
        $mappedFactories = MeasurableType::all()->map(function (MeasurableType $measurableType) use ($measurableFactories) {
            $match = $measurableFactories->first(function (MeasurableFactory $factory) use ($measurableType) {
                return $factory->getMeasurableTypeID() === $measurableType->id;
            });
            return $match ?: MeasurableFactory::new()->forMeasurableType($measurableType);
        });

        $clone = clone $this;
        $clone->measurableFactories = $mappedFactories;
        return $clone;
    }

    public function withPlayerSpirit(PlayerSpiritFactory $playerSpiritFactory = null)
    {
        $clone = clone $this;
        $clone->playerSpiritFactory = $playerSpiritFactory ?: PlayerSpiritFactory::new();
        return $clone;
    }

    public function withItems(Collection $itemFactories = null)
    {
        $clone = clone $this;
        $clone->withItems = true;
        $clone->itemFactories = $itemFactories;
        return $clone;
    }

    /**
     * @param Hero $hero
     * @return Collection
     * @throws \Exception
     */
    protected function getDefaultItemFactories(Hero $hero)
    {
        $baseFactory = ItemFactory::new()
            ->maxItemTypeGrade(20)
            ->maxMaterialGrade(20);

        switch($hero->heroClass->name) {
            case HeroClass::WARRIOR:
                return $this->getWarriorItemFactories($baseFactory);
            case HeroClass::RANGER:
                return $this->getRangerItemFactories($baseFactory);
            case HeroClass::SORCERER:
                return $this->getSorcererItemFactories($baseFactory);
        }
        throw new \Exception("No default items set up for hero class: " . $hero->heroClass->name);
    }

    protected function getWarriorItemFactories(ItemFactory $baseFactory)
    {
        $weaponFactory = $baseFactory->fromItemBases([
            ItemBase::SWORD,
            ItemBase::AXE,
            ItemBase::MACE,
        ])->withAttacks();
        $shieldFactory = $baseFactory->fromItemBases([
            ItemBase::SHIELD
        ]);
        $helmetFactory = $baseFactory->fromItemBases([
            ItemBase::HELMET
        ]);
        $armorFactory = $baseFactory->fromItemBases([
            ItemBase::HEAVY_ARMOR
        ]);
        return collect([
            $weaponFactory,
            $shieldFactory,
            $helmetFactory,
            $armorFactory
        ]);
    }

    protected function getRangerItemFactories(ItemFactory $baseFactory)
    {
        $weaponFactory = $baseFactory->fromItemBases([
            ItemBase::BOW,
            ItemBase::CROSSBOW,
        ])->withAttacks();
        $glovesFactory = $baseFactory->fromItemBases([
            ItemBase::GAUNTLETS
        ]);
        $armorFactory = $baseFactory->fromItemBases([
            ItemBase::LIGHT_ARMOR
        ]);
        return collect([
            $weaponFactory,
            $glovesFactory,
            $armorFactory
        ]);
    }

    protected function getSorcererItemFactories(ItemFactory $baseFactory)
    {
        $weaponFactory = $baseFactory->fromItemBases([
            ItemBase::STAFF,
            ItemBase::ORB,
        ])->withAttacks();
        $glovesFactory = $baseFactory->fromItemBases([
            ItemBase::GLOVES
        ]);
        $capFactory = $baseFactory->fromItemBases([
            ItemBase::CAP
        ]);
        $armorFactory = $baseFactory->fromItemBases([
            ItemBase::ROBES
        ]);
        return collect([
            $weaponFactory,
            $glovesFactory,
            $capFactory,
            $armorFactory
        ]);
    }

    public function beginnerWarrior()
    {
        $itemFactory = ItemFactory::new();

        $clone = $this
            ->heroClass(HeroClass::WARRIOR)
            ->withMeasurables()
            ->combatPosition(CombatPosition::FRONT_LINE)
            ->withItems(collect([
            $itemFactory->beginnerSword(),
            $itemFactory->beginnerShield(),
            $itemFactory->beginnerHeavyArmor()
        ]));
        return $clone;
    }

    public function beginnerRanger()
    {
        $itemFactory = ItemFactory::new();

        $clone = $this
            ->heroClass(HeroClass::RANGER)
            ->withMeasurables()
            ->combatPosition(CombatPosition::BACK_LINE)
            ->withItems(collect([
            $itemFactory->beginnerBow(),
            $itemFactory->beginnerLightArmor(),
        ]));
        return $clone;
    }

    public function beginnerSorcerer()
    {
        $itemFactory = ItemFactory::new();

        $clone = $this
            ->heroClass(HeroClass::SORCERER)
            ->withMeasurables()
            ->combatPosition(CombatPosition::BACK_LINE)
            ->withItems(collect([
            $itemFactory->beginnerStaff(),
            $itemFactory->beginnerRobes(),
        ]));
        return $clone;
    }

    public function withCompletedGamePlayerSpirit()
    {
        $playerFactory = PlayerFactory::new()->forTeam(TeamFactory::new()->forLeague(League::NFL));
        $playerGameLogFactory = PlayerGameLogFactory::new()->withPlayer($playerFactory)->goodRunningBackGame();
        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);
        return $this->withPlayerSpirit($playerSpiritFactory);
    }
}
