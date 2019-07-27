<?php

use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use Illuminate\Database\Seeder;

class PlayerSpiritsSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        $week = Week::makeForNow();
        $week->save();

        $leagues = League::query()->with('sport')->get();
        /** @var PositionCollection $positions */
        $positions = Position::query()->with('sport')->get();

        foreach(range(1,40) as $gameCount) {

            /** @var League $league */
            $league = $leagues->random();

            /** @var Team $homeTeam */
            $homeTeam = factory(Team::class)->create([
                'league_id' => $league->id
            ]);
            /** @var Team $awayTeam */
            $awayTeam = factory(Team::class)->create([
                'league_id' => $league->id
            ]);

            $hoursToAdd = random_int(0, 12);
            $gameStartsAt = $week->everything_locks_at->addHours($hoursToAdd);

            $game = factory(Game::class)->create([
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'starts_at' => $gameStartsAt
            ]);

            // Home Team
            foreach(range(1, 12) as $spiritCount) {
                $this->creatPlayerSpirit($week, $game, $homeTeam, $positions);
            }

            // Away Team
            foreach(range(1, 12) as $spiritCount) {
                $this->creatPlayerSpirit($week, $game, $awayTeam, $positions);
            }
        }
    }

    /**
     * @param Week $week
     * @param Game $game
     * @param Team $team
     * @param PositionCollection $positions
     * @throws Exception
     */
    protected function creatPlayerSpirit(Week $week, Game $game, Team $team, PositionCollection $positions)
    {
        /** @var Player $player */
        $player = factory(Player::class)->create([
            'team_id' => $team->id
        ]);

        $posCount = random_int(1, 3);
        $positionsToAttach = $positions->filter(function (Position $position) use ($team) {
            return $position->sport_id = $team->league->sport_id;
        })->shuffle()->take($posCount);

        $mostValuablePosition = $positionsToAttach->sortByPositionValue()->first();
        $minEssenceCost = $mostValuablePosition->getMinimumEssenceCost();
        $essenceCost = random_int($minEssenceCost, $minEssenceCost * 2.25);

        $player->positions()->attach($positionsToAttach->pluck('id')->toArray());

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'game_id' => $game->id,
            'player_id' => $player->id,
            'essence_cost' => $essenceCost
        ]);
    }
}
