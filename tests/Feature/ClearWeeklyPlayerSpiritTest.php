<?php

namespace Tests\Feature;

use App\Domain\Actions\ClearWeeklyPlayerSpirit;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerSpiritFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class ClearWeeklyPlayerSpiritTest extends TestCase
{
    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        $this->currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_is_no_spirit_attached_to_hero()
    {
        $hero = HeroFactory::new()->create();
        $this->assertNull($hero->playerSpirit);

        try {
            /** @var ClearWeeklyPlayerSpirit $domainAction */
            $domainAction = app(ClearWeeklyPlayerSpirit::class);
            $domainAction->execute($hero);
        } catch (\Exception $exception) {
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_spirit_belongs_to_a_current_non_finalizing_week()
    {
        /** @var Week $week */
        $week = factory(Week::class)->states('as-current', 'adventuring-closed')->create();
        $this->assertEquals(CurrentWeek::id(), $week->id);
        $playerSpiritFactory = PlayerSpiritFactory::new()->forWeek($week);
        $hero = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory)->create();
        try {
            /** @var ClearWeeklyPlayerSpirit $domainAction */
            $domainAction = app(ClearWeeklyPlayerSpirit::class);
            $domainAction->execute($hero);
        } catch (\Exception $exception) {
            return;
        }
        $this->fail('Exception not thrown');
    }

    /**
     * @test
     */
    public function it_will_remove_the_player_spirit_from_the_hero()
    {
        $playerSpiritFactory = PlayerSpiritFactory::new()->forWeek($this->currentWeek);
        $hero = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory)->create();
        $this->assertNotNull($hero->playerSpirit);

        /** @var ClearWeeklyPlayerSpirit $domainAction */
        $domainAction = app(ClearWeeklyPlayerSpirit::class);
        $domainAction->execute($hero);

        $this->assertNull($hero->fresh()->playerSpirit);
    }

    /**
     * @test
     */
    public function it_will_remove_the_player_spirit_from_a_previous_week_even_if_the_current_week_is_not_finalizing()
    {
        $previousWeek = factory(Week::class)->create([
            'made_current_at' => Date::now()->subWeek()
        ]);
            /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current', 'adventuring-closed')->create();
        $this->assertEquals(CurrentWeek::id(), $currentWeek->id);


        $playerSpiritFactory = PlayerSpiritFactory::new()->forWeek($previousWeek);
        $hero = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory)->create();
        $this->assertNotNull($hero->playerSpirit);

        /** @var ClearWeeklyPlayerSpirit $domainAction */
        $domainAction = app(ClearWeeklyPlayerSpirit::class);
        $domainAction->execute($hero);

        $this->assertNull($hero->fresh()->playerSpirit);
    }
}
