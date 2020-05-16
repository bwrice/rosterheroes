<?php

use Illuminate\Database\Seeder;

class SuperstarSpiritsSeeder extends Seeder
{
    /**
     * @param \App\Domain\Actions\CreatePlayerSpiritAction $createPlayerSpiritAction
     */
    public function run(\App\Domain\Actions\CreatePlayerSpiritAction $createPlayerSpiritAction)
    {
        $this->createMahomes($createPlayerSpiritAction);
    }

    protected function createMahomes(\App\Domain\Actions\CreatePlayerSpiritAction $createPlayerSpiritAction)
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
            10698,
            63,
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
