<?php

namespace Tests\Feature;

use App\Domain\Actions\CalculateHeroFantasyPower;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\StatType;
use App\Exceptions\CalculateHeroFantasyPowerException;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\PlayerStatFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculateHeroFantasyPowerTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_throw_an_exception_if_no_player_spirit_for_hero()
    {
        $hero = HeroFactory::new()->create();
        $this->assertNull($hero->playerSpirit);

        try {
            /** @var CalculateHeroFantasyPower $domainAction */
            $domainAction = app(CalculateHeroFantasyPower::class);
            $domainAction->execute($hero);

        } catch (CalculateHeroFantasyPowerException $exception) {
            $this->assertEquals(CalculateHeroFantasyPowerException::CODE_NO_PLAYER_SPIRIT, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_no_player_game_log()
    {
        $playerSpiritFactory = PlayerSpiritFactory::new();
        $hero = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory)->create();
        $this->assertNull($hero->playerSpirit->playerGameLog);

        try {
            /** @var CalculateHeroFantasyPower $domainAction */
            $domainAction = app(CalculateHeroFantasyPower::class);
            $domainAction->execute($hero);

        } catch (CalculateHeroFantasyPowerException $exception) {
            $this->assertEquals(CalculateHeroFantasyPowerException::CODE_NO_PLAYER_GAME_LOG, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function a_game_log_with_no_stats_will_return_a_fantasy_power_of_zero()
    {
        $playerGameLogFactory = PlayerGameLogFactory::new();
        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);
        $hero = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory)->create();

        /** @var CalculateHeroFantasyPower $domainAction */
        $domainAction = app(CalculateHeroFantasyPower::class);
        $fantasyPower = $domainAction->execute($hero);
        $this->assertSame(0, $fantasyPower);
    }

    /**
     * @test
     */
    public function it_will_have_a_higher_fantasy_power_with_higher_amount_of_same_stat()
    {
        // get positive stat type
        do {
            /** @var StatType $statType */
            $statType = StatType::query()->inRandomOrder()->first();
        } while($statType->getBehavior()->getPointsPer() < 0);

        $playerStatFactory = PlayerStatFactory::new()->forStatType($statType->name);
        $playerGameLogFactory = PlayerGameLogFactory::new()->withStats(collect([
            $playerStatFactory->withAmount(1) // amount 1
        ]));
        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);
        $hero = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory)->create();

        /** @var CalculateHeroFantasyPower $domainAction */
        $domainAction = app(CalculateHeroFantasyPower::class);
        $fantasyPowerOne = $domainAction->execute($hero);

        $playerStatFactory = PlayerStatFactory::new()->forStatType($statType->name)->withAmount(1);
        $playerGameLogFactory = PlayerGameLogFactory::new()->withStats(collect([
            $playerStatFactory->withAmount(2) // amount 2
        ]));
        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);
        $hero = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory)->create();

        /** @var CalculateHeroFantasyPower $domainAction */
        $domainAction = app(CalculateHeroFantasyPower::class);
        $fantasyPowerTwo = $domainAction->execute($hero);

        $this->assertGreaterThan($fantasyPowerOne, $fantasyPowerTwo);
    }
}
