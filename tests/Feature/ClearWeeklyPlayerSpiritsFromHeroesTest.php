<?php

namespace Tests\Feature;

use App\Domain\Actions\WeekFinalizing\ClearWeeklyPlayerSpiritsFromHeroes;
use App\Domain\Models\Hero;
use App\Domain\Models\Week;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Jobs\ClearWeeklyPlayerSpiritJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ClearWeeklyPlayerSpiritsFromHeroesTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();
        Hero::query()->update(['player_spirit_id' => null]);
        $this->currentWeek = factory(Week::class)->states('as-current', 'finalizing')->create();
    }

    /**
     * @test
     */
    public function it_will_dispatch_jobs_for_heroes_with_player_spirits_for_the_current_week()
    {
        $playerSpiritFactory = PlayerSpiritFactory::new()->forWeek($this->currentWeek);
        $heroFactory = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory);
        $count = rand(5, 10);
        $heroes = collect();
        for ($i = 1; $i <= $count; $i++) {
            $heroes->push($heroFactory->create());
        }

        Queue::fake();

        /** @var ClearWeeklyPlayerSpiritsFromHeroes $domainAction */
        $domainAction = app(ClearWeeklyPlayerSpiritsFromHeroes::class);
        $domainAction->execute();

        Queue::assertPushed(ClearWeeklyPlayerSpiritJob::class, $count);
    }

    /**
     * @test
     */
    public function it_will_not_dispatch_jobs_for_heroes_without_a_player_spirit_attached()
    {
        $playerSpiritFactory = PlayerSpiritFactory::new()->forWeek($this->currentWeek);
        $heroWithSpirit = HeroFactory::new()->withPlayerSpirit($playerSpiritFactory)->create();

        $heroWithoutSpirit = HeroFactory::new()->create();
        $this->assertNull($heroWithoutSpirit->playerSpirit);

        Queue::fake();

        /** @var ClearWeeklyPlayerSpiritsFromHeroes $domainAction */
        $domainAction = app(ClearWeeklyPlayerSpiritsFromHeroes::class);
        $domainAction->execute();

        Queue::assertPushed(ClearWeeklyPlayerSpiritJob::class, function (ClearWeeklyPlayerSpiritJob $job) use ($heroWithSpirit) {
            return $heroWithSpirit->id === $job->hero->id;
        });
        Queue::assertNotPushed(ClearWeeklyPlayerSpiritJob::class, function (ClearWeeklyPlayerSpiritJob $job) use ($heroWithoutSpirit) {
            return $heroWithoutSpirit->id === $job->hero->id;
        });
    }
}
