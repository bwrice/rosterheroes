<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;

class AutoAttachSpiritToHeroAction
{
    /**
     * @var AddSpiritToHeroAction
     */
    private $addSpiritToHeroAction;

    public function __construct(AddSpiritToHeroAction $addSpiritToHeroAction)
    {
        $this->addSpiritToHeroAction = $addSpiritToHeroAction;
    }

    /**
     * @param Hero $hero
     * @return Hero
     * @throws \App\Exceptions\HeroPlayerSpiritException
     */
    public function execute(Hero $hero)
    {
        if ($hero->player_spirit_id) {
            return $hero;
        }

        $squad = $hero->squad;
        $maxEssenceCost = (int) floor($squad->spirit_essence/$squad->heroes()->count());
        $validPositionNames = $hero->heroRace->positions->pluck('name')->toArray();

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = PlayerSpirit::query()
            ->availableForSquad($hero->squad)
            ->forCurrentWeek()
            ->withPositions($validPositionNames)
            ->maxEssenceCost($maxEssenceCost)
            ->orderByDesc('essence_cost')
            ->first();

        if ($playerSpirit) {
            return $this->addSpiritToHeroAction->execute($hero, $playerSpirit);
        }
        return $hero;
    }
}
