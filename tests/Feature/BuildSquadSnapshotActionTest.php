<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildSquadSnapshotAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\BuildSquadSnapshotException;
use App\Facades\CurrentWeek;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildSquadSnapshotActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Hero */
    protected $heroOne;

    /** @var Hero */
    protected $heroTwo;

    /** @var Week */
    protected $week;

    /** @var BuildSquadSnapshotAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->week = factory(Week::class)->state('as-current')->create();
        $this->squad = factory(Squad::class)->create();
        $this->heroOne = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);
        $this->heroTwo = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);

        $this->domainAction = app(BuildSquadSnapshotAction::class);
    }

    /**
    * @test
    */
    public function it_will_throw_an_exception_if_current_week_is_not_ready_for_finalizing()
    {
        CurrentWeek::partialMock()->shouldReceive('finalizing')->andReturn(false);
        try {
            $this->domainAction->execute($this->squad->fresh());
        } catch (BuildSquadSnapshotException $exception) {
            $this->assertEquals(BuildSquadSnapshotException::CODE_WEEK_NOT_FINALIZED, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
    * @test
    */
    public function it_will_create_a_squad_snapshot()
    {
        CurrentWeek::partialMock()->shouldReceive('finalizing')->andReturn(true);
        $squadSnapshot = $this->domainAction->execute($this->squad->fresh());
        $this->assertEquals($this->squad->id, $squadSnapshot->squad_id);
        $this->assertEquals($this->week->id, $squadSnapshot->week_id);
    }
}
