<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\Position;
use App\Domain\Models\Squad;
use App\Domain\Models\Team;
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
            (int) ceil($this->availableSpiritEssence/$this->heroesWithoutSpiritsCount) + (1000 * $this->heroesWithoutSpiritsCount) :
            $this->availableSpiritEssence;
        $minSpiritEssence = min(7500, $maxSpiritEssence - (500 * ($this->heroesWithoutSpiritsCount + 1)));
        $query->whereBetween('essence_cost', [$minSpiritEssence, $maxSpiritEssence]);

        // filter out spirits with minimum essence cost because they are likely not playing
        $flatCosts = Position::query()->get()->map(function (Position $position) {
            return $position->getDefaultEssenceCost();
        })->unique()->toArray();
        $query->whereNotIn('essence_cost', $flatCosts);

        $possibleSpirits = $query->inRandomOrder()
            ->with(['playerGameLog.team', 'playerGameLog.player'])
            ->limit(20)
            ->get();

        $spirit = $this->findIdealSpirit($possibleSpirits);

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

    protected function findIdealSpirit(Collection $possibleSpirits)
    {
        // Map spirits into teams
        $teams = $possibleSpirits->map(function (PlayerSpirit $playerSpirit) {
            return $playerSpirit->playerGameLog->team;
        })->unique(function (Team $team) {
            return $team->id;
        });

        // Load recent games for teams
        $teams->load(['awayGames' => function($builder) {
            /** @var Builder $builder */
            return $builder->orderByDesc('starts_at')->limit(10);
        }, 'homeGames' => function($builder) {
            /** @var Builder $builder */
            return $builder->orderByDesc('starts_at')->limit(10);
        }]);

        $recentGames = collect();
        $teams->each(function (Team $team) use (&$recentGames) {
            $recentGames = $recentGames->merge($team->homeGames);
            $recentGames = $recentGames->merge($team->awayGames);
        });

        $recentGameIDs = $recentGames->unique(function (Game $game) {
            return $game->id;
        })->pluck('id')->toArray();

        // Map spirits into players
        $players = $possibleSpirits->map(function (PlayerSpirit $playerSpirit) {
            return $playerSpirit->playerGameLog->player;
        })->unique(function (Player $player) {
            return $player->id;
        });

        // Load game logs for players with ids matching recent games
        $players->load(['playerGameLogs' => function ($builder) use ($recentGameIDs) {
            /** @var Builder $builder */
            return $builder->whereIn('game_id', $recentGameIDs);
        }]);

        $gameLogs = new \Illuminate\Database\Eloquent\Collection();
        $players->each(function (Player $player) use (&$gameLogs) {
            $gameLogs = $gameLogs->merge($player->playerGameLogs);
        });

        $gameLogs->load('playerStats.statType');

        /*
         * Order spirits by the sum of the fantasy sports earned in the recent game logs already
         * queried for and return the the one with the most points
         */
        return $possibleSpirits->sortByDesc(function (PlayerSpirit $playerSpirit) use ($gameLogs) {
            return $gameLogs->filter(function (PlayerGameLog $gameLog) use ($playerSpirit) {
                return $playerSpirit->playerGameLog->player_id === $gameLog->player_id;
            })->sum(function (PlayerGameLog $playerGameLog) {
                return $playerGameLog->playerStats->sum(function (PlayerStat $playerStat) {
                    return $playerStat->getFantasyPoints();
                });
            });
        })->first();
    }
}
