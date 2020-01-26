<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AutoAttachSpiritToHeroAction
{
    public const TOP_SPIRITS_TO_CONSIDER_COUNT = 40;

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
        $squadHeroes = $squad->heroes()->with('playerSpirit')->get();
        $maxEssenceCost = $this->getMaxEssenceCost($squadHeroes, $squad->spirit_essence);

        $validPositionNames = $hero->heroRace->positions->pluck('name')->toArray();
        $baseQuery = $this->buildBaseQuery($squad, $validPositionNames, $maxEssenceCost);

        if ($this->onlyLastHeroNeedsSpirit($squadHeroes)) {
            // Get the highest essence cost spirit we can afford
            $playerSpirit = $baseQuery->first();
        } else {
            $playerSpirit = $this->getRandomSpiritFromTopChoices($baseQuery);
        }

        if ($playerSpirit) {
            return $this->addSpiritToHeroAction->execute($hero, $playerSpirit);
        } else {
            return $hero;
        }
    }

    protected function getMaxEssenceCost(Collection $squadHeroes, int $squadSpiritEssence)
    {
        if ($this->onlyLastHeroNeedsSpirit($squadHeroes)) {
            // If last hero, return essence remaining for squad
            $essenceUsed = $squadHeroes->sum(function (Hero $hero) {
                if ($hero->player_spirit_id) {
                    return $hero->playerSpirit->essence_cost;
                }
                return 0;
            });
            return $squadSpiritEssence - $essenceUsed;
        } else {
            // If multiple heroes need player spirits, divide essence cost evenly among heroes
            return (int) floor($squadSpiritEssence / $squadHeroes->count());
        }
    }

    protected function onlyLastHeroNeedsSpirit(Collection $squadHeroes)
    {
        $heroesMissingSpirits = $squadHeroes->filter(function (Hero $hero) {
            return is_null($hero->player_spirit_id);
        });
        return 1 === $heroesMissingSpirits->count();
    }

    protected function buildBaseQuery(Squad $squad, array $validPositionNames, int $maxEssenceCost)
    {
        return PlayerSpirit::query()
            ->availableForSquad($squad)
            ->forCurrentWeek()
            ->withPositions($validPositionNames)
            ->maxEssenceCost($maxEssenceCost)
            ->orderByDesc('essence_cost');
    }

    protected function getRandomSpiritFromTopChoices(Builder $baseQuery)
    {
        return $baseQuery->take(self::TOP_SPIRITS_TO_CONSIDER_COUNT)->inRandomOrder()->first();
    }
}
