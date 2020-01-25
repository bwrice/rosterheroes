<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Collections\PlayerSpiritCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Squad;

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
        $validPositionNames = $hero->heroRace->positions->pluck('name')->toArray();

        $validPlayerSpirits = $this->getValidPlayerSpirits($validPositionNames, $squad->heroes()->count(), $squad->spirit_essence);
        $playerSpirit = $this->filterInUsePlayerSpirits($validPlayerSpirits, $squad)->first();

        if ($playerSpirit) {
            return $this->addSpiritToHeroAction->execute($hero, $playerSpirit);
        }
        return $hero;
    }

    /**
     * @param array $validPositionNames
     * @param int $heroesCount
     * @param int $totalEssence
     * @return PlayerSpiritCollection
     */
    protected function getValidPlayerSpirits(array $validPositionNames, int $heroesCount, int $totalEssence)
    {
        $maxEssenceCost = (int) floor($totalEssence/$heroesCount);

        return PlayerSpirit::query()
            ->forCurrentWeek()
            ->withPositions($validPositionNames)
            ->maxEssenceCost($maxEssenceCost)
            ->orderByDesc('essence_cost')
            ->take($heroesCount)->get();
    }

    /**
     * @param PlayerSpiritCollection $playerSpirits
     * @param Squad $squad
     * @return PlayerSpiritCollection
     */
    protected function filterInUsePlayerSpirits(PlayerSpiritCollection $playerSpirits, Squad $squad)
    {
        $heroes = $squad->heroes;
        return $playerSpirits->filter(function (PlayerSpirit $playerSpirit) use ($heroes) {
            $inUse = $heroes->first(function (Hero $hero) use ($playerSpirit) {
                return $hero->player_spirit_id === $playerSpirit->id;
            });
            return is_null($inUse);
        });
    }
}
