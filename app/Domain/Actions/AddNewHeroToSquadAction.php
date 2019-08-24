<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 2/3/19
 * Time: 8:40 AM
 */

namespace App\Domain\Actions;


use App\Domain\Models\ItemBlueprint;
use App\Exceptions\HeroPostNotFoundException;
use App\Exceptions\InvalidHeroClassException;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroRace;
use App\Domain\Models\HeroRank;
use App\Domain\Models\Squad;

class AddNewHeroToSquadAction
{
    /**
     * @var CreateHeroAction
     */
    private $createHeroAction;
    /**
     * @var GenerateItemFromBlueprintAction
     */
    private $generateItemFromBlueprintAction;
    /**
     * @var FillSlotsWithItemAction
     */
    private $fillSlotAction;

    public function __construct(
        CreateHeroAction $createHeroAction,
        GenerateItemFromBlueprintAction $generateItemFromBlueprintAction,
        FillSlotsWithItemAction $fillSlotAction)
    {
        $this->createHeroAction = $createHeroAction;
        $this->generateItemFromBlueprintAction = $generateItemFromBlueprintAction;
        $this->fillSlotAction = $fillSlotAction;
    }

    /**
     * @param Squad $squad
     * @param string $heroName
     * @param HeroClass $heroClass
     * @param HeroRace $heroRace
     * @return Hero
     * @throws HeroPostNotFoundException
     * @throws InvalidHeroClassException
     */
    public function execute(Squad $squad, string $heroName, HeroClass $heroClass, HeroRace $heroRace): Hero
    {
        $heroPost= $squad->getHeroPostAvailability()->heroRace($heroRace)->first();
        if (! $heroPost) {
            throw new HeroPostNotFoundException($heroRace);
        }

        if (! $squad->getHeroClassAvailability()->contains($heroClass)) {
            throw new InvalidHeroClassException($heroClass);
        }

        // Create hero
        $hero = $this->createHeroAction->execute($heroName, $heroClass, $heroRace, HeroRank::getStarting());

        // Attach hero to hero post
        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        // Create new hero items and slot them
        $heroClass->getBehavior()->getStartItemBlueprints()->each(function (ItemBlueprint $itemBlueprint) use ($hero) {
            $hero = $hero->fresh();
            $item = $this->generateItemFromBlueprintAction->execute($itemBlueprint);
            $this->fillSlotAction->execute($hero, $item);
        });

        return $hero->fresh();
    }
}
