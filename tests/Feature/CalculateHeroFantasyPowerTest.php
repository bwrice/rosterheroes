<?php

namespace Tests\Feature;

use App\Domain\Actions\CalculateHeroFantasyPower;
use App\Domain\Models\Enchantment;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\StatType;
use App\Exceptions\CalculateHeroFantasyPowerException;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\PlayerStatFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalculateHeroFantasyPowerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_return_zero_if_no_player_spirit()
    {
        $hero = HeroFactory::new()->create();
        $this->assertNull($hero->playerSpirit);

        /** @var CalculateHeroFantasyPower $domainAction */
        $domainAction = app(CalculateHeroFantasyPower::class);
        $fantasyPower = $domainAction->execute($hero);
        $this->assertSame(0, $fantasyPower);
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
        $hero = HeroFactory::new()->withMeasurables()->withPlayerSpirit($playerSpiritFactory)->create();

        /** @var CalculateHeroFantasyPower $domainAction */
        $domainAction = app(CalculateHeroFantasyPower::class);
        $fantasyPowerOne = $domainAction->execute($hero);

        $playerStatFactory = PlayerStatFactory::new()->forStatType($statType->name)->withAmount(1);
        $playerGameLogFactory = PlayerGameLogFactory::new()->withStats(collect([
            $playerStatFactory->withAmount(2) // amount 2
        ]));
        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);
        $hero = HeroFactory::new()->withMeasurables()->withPlayerSpirit($playerSpiritFactory)->create();

        /** @var CalculateHeroFantasyPower $domainAction */
        $domainAction = app(CalculateHeroFantasyPower::class);
        $fantasyPowerTwo = $domainAction->execute($hero);

        $this->assertGreaterThan($fantasyPowerOne, $fantasyPowerTwo);
    }

    /**
     * @test
     */
    public function having_the_corresponding_quality_boosted_for_the_stat_type_will_increase_fantasy_power()
    {
        /** @var MeasurableType $measurableType */
        $measurableType = MeasurableType::query()->where('name', '=', MeasurableType::BALANCE)->first();

        // get positive stat type
        do {
            /** @var StatType $statType */
            $statType = $measurableType->statTypes()->inRandomOrder()->first();
        } while($statType->getBehavior()->getPointsPer() < 0);

        $playerStatFactory = PlayerStatFactory::new()->forStatType($statType->name);
        $playerGameLogFactory = PlayerGameLogFactory::new()->withStats(collect([
            $playerStatFactory->withAmount(1)
        ]));
        $playerSpiritFactory = PlayerSpiritFactory::new()->withPlayerGameLog($playerGameLogFactory);

        $baseHeroFactory = HeroFactory::new()->withMeasurables()->withPlayerSpirit($playerSpiritFactory);
        $hero = $baseHeroFactory->create();

        /** @var CalculateHeroFantasyPower $domainAction */
        $domainAction = app(CalculateHeroFantasyPower::class);
        $fantasyPowerOne = $domainAction->execute($hero);

        $enchantment = Enchantment::query()->whereHas('measurableBoosts', function (Builder $builder) use ($measurableType) {
            return $builder->where('measurable_type_id', '=', $measurableType->id);
        })->inRandomOrder()->first();
        $itemFactory = ItemFactory::new()->withEnchantments(collect([$enchantment]));

        $heroTwo = $baseHeroFactory->withItems(collect([$itemFactory]))->create();

        /** @var CalculateHeroFantasyPower $domainAction */
        $domainAction = app(CalculateHeroFantasyPower::class);
        $fantasyPowerTwo = $domainAction->execute($heroTwo);

        $this->assertGreaterThan($fantasyPowerOne, $fantasyPowerTwo);
    }
}
