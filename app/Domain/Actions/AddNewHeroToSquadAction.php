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
     * @var AddItemToHasItems
     */
    private $addItemToHasItemsAction;

    public function __construct(
        CreateHeroAction $createHeroAction,
        GenerateItemFromBlueprintAction $generateItemFromBlueprintAction,
        AddItemToHasItems $addItemToHasItemsAction)
    {
        $this->createHeroAction = $createHeroAction;
        $this->generateItemFromBlueprintAction = $generateItemFromBlueprintAction;
        $this->addItemToHasItemsAction = $addItemToHasItemsAction;
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
        $heroPost = $squad->getEmptyHeroPosts()->filterByHeroRace($heroRace)->first();
        if (! $heroPost) {
            throw new HeroPostNotFoundException($heroRace);
        }

        if (! $squad->getHeroClassAvailability()->contains($heroClass)) {
            throw new InvalidHeroClassException($heroClass);
        }

        // Create hero
        $hero = $this->createHeroAction->execute($heroName, $squad, $heroClass, $heroRace, HeroRank::getStarting());

        // Create new hero items and slot them
        $heroClass->getBehavior()->getStartItemBlueprints()->each(function (ItemBlueprint $itemBlueprint) use ($hero) {
            $hero = $hero->fresh();
            $item = $this->generateItemFromBlueprintAction->execute($itemBlueprint);
            $this->addItemToHasItemsAction->execute($item, $hero);
        });

        return $hero->fresh();
    }
}
