<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FindSpiritsToEmbodyHeroes
{
    protected Collection $spiritsInUse;
    protected Collection $embodyArrays;
    protected ?Week $currentWeek = null;
    protected int $availableSpiritEssence = 0;
    protected int $heroesWithoutSpiritsCount = 0;

    public function __construct()
    {
        $this->spiritsInUse = collect();
        $this->embodyArrays = collect();
    }

    /**
     * @param Squad $npc
     * @return Collection
     */
    public function execute(Squad $npc)
    {
        $this->currentWeek = CurrentWeek::get();
        $heroesWithoutSpirits = $npc->heroes()->whereNull('player_spirit_id')->get();
        $this->heroesWithoutSpiritsCount = $heroesWithoutSpirits->count();
        $this->availableSpiritEssence = $npc->availableSpiritEssence();
        $heroesWithoutSpirits->shuffle()->each(function (Hero $hero) {
            $this->findSpiritForHero($hero);
        });
        return $this->embodyArrays;
    }

    protected function findSpiritForHero(Hero $hero)
    {
        // Build initial query for spirits for current week with valid positions for hero's race
        $validPositionIDs = $hero->heroRace->positions()->pluck('id')->toArray();
        $query = PlayerSpirit::query()->forWeek($this->currentWeek)->whereHas('playerGameLog', function (Builder $builder) use ($validPositionIDs) {
            $builder->whereHas('player', function (Builder $builder) use ($validPositionIDs) {
                $builder->whereHas('positions', function (Builder $builder) use ($validPositionIDs) {
                    $builder->whereIn('id', $validPositionIDs);
                });
            });
        });

        // filter out spirits already in use by squad
        $query->whereNotIn('id', $this->spiritsInUse->pluck('id')->toArray());

        // get spirit with a reasonable essence cost based on remaining spirit essence of the npc
        $maxSpiritEssence = $this->heroesWithoutSpiritsCount > 1 ?
            (int) ceil($this->availableSpiritEssence/$this->heroesWithoutSpiritsCount) + 4000 :
            $this->availableSpiritEssence;
        $minSpiritEssence = min(7500, $maxSpiritEssence - 2000);
        $query->whereBetween('essence_cost', [$minSpiritEssence, $maxSpiritEssence]);

        // filter out spirits with minimum essence cost because they are likely not playing
        $flatCosts = Position::query()->get()->map(function (Position $position) {
            return $position->getDefaultEssenceCost();
        })->unique()->toArray();
        $query->whereNotIn('essence_cost', $flatCosts);

        $spirit = $query->inRandomOrder()->first();
        if ($spirit) {
            $this->availableSpiritEssence -= $spirit->essence_cost;
            $this->spiritsInUse->push($spirit);
            $this->heroesWithoutSpiritsCount--;
            $this->embodyArrays->push([
                'hero' => $hero,
                'player_spirit' => $spirit
            ]);
        }
    }
}
