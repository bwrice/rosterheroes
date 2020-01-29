<?php

namespace Tests\Feature;

use App\AttackSnapshot;
use App\Domain\Actions\BuildAttackSnapshotAction;
use App\Domain\Models\Attack;
use App\Domain\Models\Week;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildAttackSnapshotActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var Attack */
    protected $attack;

    /** @var BuildAttackSnapshotAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->week = factory(Week::class)->states('as-current', 'finalizing')->create();
        $this->attack = factory(Attack::class)->create();
        $this->domainAction = app(BuildAttackSnapshotAction::class);
    }

    /**
     * @test
     */
    public function it_will_create_an_attack_snapshot_for_the_attack_and_current_week()
    {
        /** @var AttackSnapshot $attackSnapshot */
        $attackSnapshot = $this->domainAction->execute($this->attack);
        $this->assertEquals($this->attack->id, $attackSnapshot->attack_id);
        $this->assertEquals($this->week->id, $attackSnapshot->week_id);
    }
}
