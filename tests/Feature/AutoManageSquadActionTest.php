<?php

namespace Tests\Feature;

use App\Domain\Actions\Testing\AutoManageSquadAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Exceptions\AutoManageSquadException;
use App\Facades\CurrentWeek;
use App\Jobs\AutoManageHeroJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AutoManageSquadActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Hero */
    protected $heroOne;

    /** @var Hero */
    protected $heroTwo;

    /** @var AutoManageSquadAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->squad = factory(Squad::class)->create();
        $this->heroOne = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);
        $this->heroTwo = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);

        $this->domainAction = app(AutoManageSquadAction::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_no_current_week()
    {
        CurrentWeek::partialMock()->shouldReceive('get')->andReturn(null);
        try {
            $this->domainAction->execute($this->squad);
        } catch (AutoManageSquadException $exception) {
            $this->assertEquals(AutoManageSquadException::CODE_NO_CURRENT_WEEK, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_the_current_week_locks_soon()
    {
        CurrentWeek::partialMock()->shouldReceive('get')->andReturn(true);
        CurrentWeek::partialMock()->shouldReceive('adventuringLocksAt')->andReturn(Date::now()->addMinutes(30));
        try {
            $this->domainAction->execute($this->squad);
        } catch (AutoManageSquadException $exception) {
            $this->assertEquals(AutoManageSquadException::CODE_CURRENT_WEEK_LOCKS_SOON, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_dispatch_auto_manage_jobs_correctly()
    {
        CurrentWeek::partialMock()->shouldReceive('get')->andReturn(true);
        CurrentWeek::partialMock()->shouldReceive('adventuringLocksAt')->andReturn(Date::now()->addDays(6));
        Queue::fake();
        $this->domainAction->execute($this->squad);

        foreach ([
                     $this->heroOne,
                     $this->heroTwo
                 ] as $hero) {

            Queue::assertPushed(function (AutoManageHeroJob $job) use ($hero) {
                return $job->hero->id === $hero->id;
            });
        }
    }
}
