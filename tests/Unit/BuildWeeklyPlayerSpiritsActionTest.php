<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildWeeklyPlayerSpiritsAction;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Jobs\CreatePlayerSpiritJob;
use App\Jobs\CreateSpiritsForGameJob;
use App\Services\Models\WeekService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildWeeklyPlayerSpiritsActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->state('as-current')->create();
    }

    /**
     * @return BuildWeeklyPlayerSpiritsAction
     */
    protected function getDomainAction()
    {
        return app(BuildWeeklyPlayerSpiritsAction::class);
    }

    /**
    * @test
    */
    public function it_will_queue_jobs_for_games_starting_at_the_same_time_adventuring_locks_at()
    {
        $game = GameFactory::new()->withStartTime($this->week->adventuring_locks_at->clone())->create();

        Queue::fake();

        $this->getDomainAction()->execute($this->week);

        Queue::assertPushed(CreateSpiritsForGameJob::class, function (CreateSpiritsForGameJob $job) use ($game) {
            return $job->game->id === $game->id;
        });
    }

    /**
    * @test
    */
    public function it_will_not_queue_jobs_for_games_before_adventuring_locks_at()
    {
        $game = GameFactory::new()
            ->withStartTime($this->week->adventuring_locks_at->subMinutes(15)->clone())
            ->create();

        Queue::fake();

        $this->getDomainAction()->execute($this->week);

        Queue::assertNotPushed(CreateSpiritsForGameJob::class, function (CreateSpiritsForGameJob $job) use ($game) {
            return $job->game->id === $game->id;
        });

    }

    /**
    * @test
    */
    public function it_will_not_queue_jobs_for_games_more_than_12_hours_after_adventuring_locks()
    {
        $game = GameFactory::new()
            ->withStartTime($this->week->adventuring_locks_at->addHours(13)->clone())
            ->create();

        Queue::fake();

        $this->getDomainAction()->execute($this->week);

        Queue::assertNotPushed(CreateSpiritsForGameJob::class, function (CreateSpiritsForGameJob $job) use ($game) {
            return $job->game->id === $game->id;
        });
    }
}
