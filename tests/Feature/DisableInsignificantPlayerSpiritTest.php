<?php

namespace Tests\Feature;

use App\Domain\Actions\DisableInsignificantPlayerSpirit;
use App\Domain\Actions\DisablePlayerSpirit;
use App\Domain\Models\Position;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class DisableInsignificantPlayerSpiritTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_not_disable_a_player_spirit_without_previous_game_logs()
    {
        $game = GameFactory::new()->create([
            'starts_at' => Date::now()->addDays(5)
        ]);
        $player = PlayerFactory::new()->withPosition(Position::query()->inRandomOrder()->first())->create();
        $gameLogFactory = PlayerGameLogFactory::new()->forGame($game)->forPlayer($player);
        $playerSpirit = PlayerSpiritFactory::new()->withPlayerGameLog($gameLogFactory)->create();

        $disableSpy = \Mockery::spy(DisablePlayerSpirit::class);

        /** @var DisableInsignificantPlayerSpirit $domainAction */
        $domainAction = app(DisableInsignificantPlayerSpirit::class);
        $result = $domainAction->execute($playerSpirit);

        $this->assertFalse($result);
        $disableSpy->shouldNotHaveReceived('execute');
    }
}
