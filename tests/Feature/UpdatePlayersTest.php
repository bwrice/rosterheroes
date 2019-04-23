<?php

namespace Tests\Feature;

use App\Domain\Collections\PositionCollection;
use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\DataTransferObjects\PlayerDTO;
use App\Domain\Models\Team;
use App\External\Stats\MockIntegration;
use App\External\Stats\StatsIntegration;
use App\Jobs\UpdatePlayers;
use App\Domain\Models\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePlayersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_update_players()
    {
        $externalID1 = uniqid();
        $positions1 = Position::inRandomOrder()->take(3)->get();
        $DTO1 = new PlayerDTO(factory(Team::class)->create(), $positions1, 'Bob', 'Johnson', $externalID1);
        $externalID2 = uniqid();
        $positions2 = Position::inRandomOrder()->take(2)->get();
        $DTO2 = new PlayerDTO(factory(Team::class)->create(), $positions2, 'David', 'Smith', $externalID2);

        $playerDTOs = collect([$DTO1, $DTO2]);

        $integration = new MockIntegration(null, $playerDTOs);
        app()->instance(StatsIntegration::class, $integration);

        UpdatePlayers::dispatchNow();

        $playerOne = Player::where('external_id', '=', $externalID1)->first();
        $this->assertNotNull($playerOne);
        // Verify positions match
        $this->assertEquals($playerOne->positions->pluck('id')->intersect($positions1->pluck('id'))->count(), $positions1->count());
        $playerTwo = Player::where('external_id', '=', $externalID2)->first();
        $this->assertNotNull($playerTwo);
        // Verify positions match
        $this->assertEquals($playerTwo->positions->pluck('id')->intersect($positions2->pluck('id'))->count(), $positions2->count());
    }

    /**
     * @test
     */
    public function it_will_update_a_player_if_their_team_has_changed()
    {
        /** @var Team $team */
        $team = factory(Team::class)->create();
        $positions = $team->league->sport->positions;
        $playerPositions = $positions->take(2);
        $externalID = uniqid();
        $playerDTO = new PlayerDTO($team, $playerPositions, 'Traded', 'Guy', $externalID);

        $integration = new MockIntegration(null, collect([$playerDTO]));
        app()->instance(StatsIntegration::class, $integration);

        UpdatePlayers::dispatchNow();

        $player = Player::where('external_id', '=', $externalID)->first();
        $this->assertEquals($team->id, $player->team->id, "Original team matches");

        /** @var Team $newTeam */
        $newTeam = factory(Team::class)->create();
        $playerDTO = new PlayerDTO($newTeam, $playerPositions, 'Traded', 'Guy', $externalID);
        $integration = new MockIntegration(null, collect([$playerDTO]));
        app()->instance(StatsIntegration::class, $integration);

        UpdatePlayers::dispatchNow();

        $player = Player::where('external_id', '=', $externalID)->first();
        $this->assertEquals($newTeam->id, $player->team->id, "New team matches");
    }

    /**
     * @test
     */
    public function it_will_update_positions_of_a_player_correctly()
    {
        /** @var Team $team */
        $league = League::nfl();
        $team = factory(Team::class)->create([
            'league_id' => $league->id
        ]);
        /** @var PositionCollection $originalPositions */
        $originalPositions = Position::query()->where('name', '=', 'Wide Receiver')->orWhere('name', '=', 'Tight End')->get();
        $this->assertEquals(2, $originalPositions->count());

        $externalID = uniqid();
        $playerDTO = new PlayerDTO($team, $originalPositions, 'Haznu', 'Pozishun', $externalID);

        $integration = new MockIntegration(null, collect([$playerDTO]));
        app()->instance(StatsIntegration::class, $integration);

        UpdatePlayers::dispatchNow();

        /** @var Player $player */
        $player = Player::where('external_id', '=', $externalID)->first();
        $this->assertEquals(2, $player->positions->count());

        /** @var PositionCollection $newPositions */
        $newPositions = Position::query()->where('name', '=', 'Running Back')->get();
        $this->assertEquals(1, $newPositions->count());

        $updatedPlayerDTO = new PlayerDTO($team, $newPositions, 'Traded', 'Guy', $externalID);

        $integration = new MockIntegration(null, collect([$updatedPlayerDTO]));
        app()->instance(StatsIntegration::class, $integration);

        UpdatePlayers::dispatchNow();

        /** @var Collection $players */
        $players = Player::where('external_id', '=', $externalID)->get();
        $this->assertEquals(1, $players->count());
        /** @var Player $player */
        $updatedPlayer = $players->first();
        $this->assertEquals(3, $updatedPlayer->positions->count());

    }
}
