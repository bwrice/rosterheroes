<?php

namespace Tests\Feature;

use App\Domain\Actions\DisableSpiritsForGame;
use App\Domain\Models\PlayerSpirit;
use App\Factories\Models\GameFactory;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Mail\SpiritRemovedFromHero;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DisableSpiritsForGameTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return DisableSpiritsForGame
     */
    protected function getDomainAction()
    {
        return app(DisableSpiritsForGame::class);
    }

    /**
     * @test
     */
    public function it_will_delete_any_player_spirits_associated_with_the_game()
    {
        $game = GameFactory::new()->create();
        $playerGameLog = PlayerGameLogFactory::new()->forGame($game);
        $spiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLog);

        $spiritOneID = $spiritFactory->create()->id;
        $spiritTwoID = $spiritFactory->create()->id;

        $this->getDomainAction()->execute($game);

        $this->assertNull(PlayerSpirit::query()->find($spiritOneID));
        $this->assertNull(PlayerSpirit::query()->find($spiritTwoID));
    }

    /**
     * @test
     */
    public function it_will_detach_deleted_spirits_from_heroes_that_embody_them()
    {
        $game = GameFactory::new()->create();
        $playerGameLog = PlayerGameLogFactory::new()->forGame($game);
        $spiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLog);

        $spiritOneID = $spiritFactory->create()->id;
        $spiritTwoID = $spiritFactory->create()->id;

        $heroOne = HeroFactory::new()->create([
            'player_spirit_id' => $spiritOneID
        ]);

        $heroTwo = HeroFactory::new()->create([
            'player_spirit_id' => $spiritTwoID
        ]);

        $this->getDomainAction()->execute($game);

        $this->assertNull($heroOne->fresh()->player_spirit_id);
        $this->assertNull($heroTwo->fresh()->player_spirit_id);
    }

    /**
     * @test
     */
    public function it_will_queue_spirit_removed_for_hero_emails()
    {

        $game = GameFactory::new()->create();
        $playerGameLog = PlayerGameLogFactory::new()->forGame($game);
        $spiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLog);

        $spiritOne = $spiritFactory->create();
        $spiritTwo = $spiritFactory->create();

        $heroOne = HeroFactory::new()->create([
            'player_spirit_id' => $spiritOne->id
        ]);

        $heroTwo = HeroFactory::new()->create([
            'player_spirit_id' => $spiritTwo->id
        ]);

        Mail::fake();

        $this->getDomainAction()->execute($game);

        Mail::assertQueued(SpiritRemovedFromHero::class, function (SpiritRemovedFromHero $mail) use ($heroOne) {
            return $mail->hero->id === $heroOne->id;
        });

        Mail::assertQueued(SpiritRemovedFromHero::class, function (SpiritRemovedFromHero $mail) use ($heroTwo) {
            return $mail->hero->id === $heroTwo->id;
        });
    }
}
