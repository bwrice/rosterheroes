<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/16/19
 * Time: 1:12 PM
 */

namespace App\Domain\Actions;


use App\Aggregates\HeroAggregate;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\SlotType;
use App\StorableEvents\HeroCreated;

class CreateNewHeroAction
{
    /**
     * @var string
     */
    private $uuid;
    /**
     * @var string
     */
    private $name;
    /**
     * @var HeroClass
     */
    private $heroClass;
    /**
     * @var HeroRace
     */
    private $heroRace;
    /**
     * @var HeroRank
     */
    private $heroRank;

    public function __construct(string $uuid, string $name, HeroClass $heroClass, HeroRace $heroRace, HeroRank $heroRank)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->heroClass = $heroClass;
        $this->heroRace = $heroRace;
        $this->heroRank = $heroRank;
    }

    public function __invoke(): Hero
    {
        /** @var HeroAggregate $aggregate */
        $aggregate = HeroAggregate::retrieve($this->uuid);
        $aggregate->recordThat(new HeroCreated(
            $this->name,
            $this->heroClass->id,
            $this->heroRace->id,
            $this->heroRank->id
        ));

        /*
         * Persist aggregate because we need an hero in the DB
         * for the rest of the creation process
         */
        $aggregate->persist();
        $hero = Hero::uuid($this->uuid);

        SlotType::heroTypes()->each(function (SlotType $slotType) use ($aggregate) {
            $aggregate->createHeroSlot($slotType->id);
        });

        // TODO: Use aggregates for measurable creation
        MeasurableType::heroTypes()->each(function (MeasurableType $measurableType) use ($hero) {
            Measurable::createWithAttributes([
                'has_measurables_type' => hero::RELATION_MORPH_MAP_KEY,
                'has_measurables_id' => $hero->id,
                'measurable_type_id' => $measurableType->id,
                'amount_raised' => 0
            ]);
        });

        // Persist slots and measurable creations
        $aggregate->persist();
        return Hero::uuid($this->uuid);
    }

    /**
     * @return HeroClass
     */
    public function getHeroClass(): HeroClass
    {
        return $this->heroClass;
    }

    /**
     * @return HeroRace
     */
    public function getHeroRace(): HeroRace
    {
        return $this->heroRace;
    }

    /**
     * @return HeroRank
     */
    public function getHeroRank(): HeroRank
    {
        return $this->heroRank;
    }
}