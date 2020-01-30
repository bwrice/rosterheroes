<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildMinionSnapshotAction;
use App\Domain\Models\Minion;
use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildMinionSnapshotActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Minion */
    protected $minion;

    /** @var Week */
    protected $currentWeek;

    /** @var BuildMinionSnapshotAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->minion = factory(Minion::class)->create();
        $this->currentWeek = factory(Week::class)->states('finalizing', 'as-current')->create();
        $this->domainAction = app(BuildMinionSnapshotAction::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_snapshot_for_the_minion_and_current_week()
    {
        $snapshot = $this->domainAction->execute($this->minion);
        $this->assertEquals($this->minion->id, $snapshot->minion_id);
        $this->assertEquals($this->currentWeek->id, $snapshot->week_id);
    }
}
