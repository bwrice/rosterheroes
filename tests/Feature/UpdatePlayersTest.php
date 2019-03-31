<?php

namespace Tests\Feature;

use App\Domain\Models\Player;
use App\Domain\DataTransferObjects\PlayerDTO;
use App\Domain\Models\Team;
use App\External\Stats\MockIntegration;
use App\External\Stats\StatsIntegration;
use App\Jobs\UpdatePlayers;
use App\Domain\Models\Position;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePlayersTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_update_teams()
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
}
