<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SuperstarSpiritsSeeder extends Seeder
{
    /**
     * @param \App\Domain\Actions\CreatePlayerSpiritAction $createPlayerSpiritAction
     */
    public function run(\App\Domain\Actions\CreatePlayerSpiritAction $createPlayerSpiritAction)
    {
        $this->createSpirit(
            $createPlayerSpiritAction,
            [
                'first_name' => 'Patrick',
                'last_name' => 'Mahomes'
            ],
            [
                'name' => 'Chiefs',
                'location' => 'Kansas City',
                'abbreviation' => 'KC'
            ],
            [
                'name' => 'Broncos',
                'location' => 'Denver',
                'abbreviation' => 'DEN'
            ],
            \App\Domain\Models\League::NFL,
            \App\Domain\Models\Position::QUARTERBACK,
            8073,
            129,
            false
        );
        $this->createSpirit(
            $createPlayerSpiritAction,
            [
                'first_name' => 'Lebron',
                'last_name' => 'James'
            ],
            [
                'name' => 'Lakers',
                'location' => 'Los Angeles',
                'abbreviation' => 'LAL'
            ],
            [
                'name' => 'Warriors',
                'location' => 'Golden State',
                'abbreviation' => 'GSW'
            ],
            \App\Domain\Models\League::NBA,
            \App\Domain\Models\Position::POWER_FORWARD,
            10118,
            371,
            false
        );
        $this->createSpirit(
            $createPlayerSpiritAction,
            [
                'first_name' => 'Mike',
                'last_name' => 'Trout'
            ],
            [
                'name' => 'Angels',
                'location' => 'Los Angeles',
                'abbreviation' => 'LAA'
            ],
            [
                'name' => 'Baltimore',
                'location' => 'Orioles',
                'abbreviation' => 'BAL'
            ],
            \App\Domain\Models\League::MLB,
            \App\Domain\Models\Position::OUTFIELD,
            6392,
            235,
            true
        );
        $this->createSpirit(
            $createPlayerSpiritAction,
            [
                'first_name' => 'Sidney',
                'last_name' => 'Crosby'
            ],
            [
                'name' => 'Penguins',
                'location' => 'Pittsburgh',
                'abbreviation' => 'PIT'
            ],
            [
                'name' => 'Capitols',
                'location' => 'Washington',
                'abbreviation' => 'WAS'
            ],
            \App\Domain\Models\League::NHL,
            \App\Domain\Models\Position::HOCKEY_CENTER,
            4156,
            72,
            true
        );
    }


    protected function createSpirit(
        \App\Domain\Actions\CreatePlayerSpiritAction $createPlayerSpiritAction,
        array $playerExtra,
        array $playerTeamExtra,
        array $opponentTeamExtra,
        string $leagueName,
        string $positionName,
        int $essenceCost,
        int $energy,
        bool $home)
    {
        $playersTeam = \App\Factories\Models\TeamFactory::new()
            ->forLeague($leagueName)
            ->create($playerTeamExtra);

        $position = \App\Domain\Models\Position::query()
            ->where('name', '=', $positionName)
            ->first();

        $player = \App\Factories\Models\PlayerFactory::new()
            ->withTeamID($playersTeam->id)
            ->withPosition($position)
            ->create($playerExtra);

        $opponent = \App\Factories\Models\TeamFactory::new()
            ->forLeague($leagueName)
            ->create($opponentTeamExtra);
        $homTeam = $home ? $playersTeam : $opponent;
        $awayTeam = $home ? $opponent : $playersTeam;
        $currentWeek = \App\Facades\CurrentWeek::get();
        $game = \App\Factories\Models\GameFactory::new()
            ->forHomeTeam($homTeam)
            ->forAwayTeam($awayTeam)
            ->forWeek($currentWeek)
            ->create();

        $playerSpirit = $createPlayerSpiritAction->execute($currentWeek, $game, $player);
        $playerSpirit->essence_cost = $essenceCost;
        $playerSpirit->energy = $energy;
        $playerSpirit->save();
    }
}
