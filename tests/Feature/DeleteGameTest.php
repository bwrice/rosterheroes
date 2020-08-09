<?php

namespace Tests\Feature;

use App\Domain\Actions\DeleteGame;
use App\Exceptions\DeleteGameException;
use App\Factories\Models\GameFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\PlayerStatFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteGameTest extends TestCase
{
    /**
     * @return DeleteGame
     */
    protected function getDomainAction()
    {
        return app(DeleteGame::class);
    }

    /**
     * @test
     */
    public function it_will_delete_the_game_given()
    {
        $game = GameFactory::new()->create();

        $this->getDomainAction()->execute($game);

        $this->assertNull($game->fresh());
    }

    /**
     * @test
     */
    public function it_will_delete_any_game_logs_associated_with_the_game()
    {
        $game = GameFactory::new()->create();
        $gameLogFactory = PlayerGameLogFactory::new()->forGame($game);
        $gameLogA = $gameLogFactory->create();
        $gameLogB = $gameLogFactory->create();

        $this->getDomainAction()->execute($game);

        $this->assertNull($gameLogA->fresh());
        $this->assertNull($gameLogB->fresh());
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_game_has_game_logs_with_stats()
    {
        $game = GameFactory::new()->create();
        $gameLog = PlayerGameLogFactory::new()->forGame($game)->create();
        $stat = PlayerStatFactory::new()->forGameLog($gameLog)->create();

        try {

            $this->getDomainAction()->execute($game);

        } catch (DeleteGameException $exception) {

            $this->assertEquals(DeleteGameException::CODE_GAME_HAS_STATS, $exception->getCode());

            $this->assertNotNull($game->fresh());
            $this->assertNotNull($gameLog->fresh());
            $this->assertNotNull($stat->fresh());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_disable_any_player_spirits_for_the_game_to_be_deleted()
    {
        $game = GameFactory::new()->create();
        $gameLog = PlayerGameLogFactory::new()->forGame($game)->create();
        $playerSpirit = PlayerSpiritFactory::new()->create([
            'player_game_log_id' => $gameLog->id
        ]);
        $hero = HeroFactory::new()->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $this->getDomainAction()->execute($game);

        $this->assertNull($playerSpirit->fresh());
        $this->assertNull($hero->fresh()->player_spirit_id);
    }
}
