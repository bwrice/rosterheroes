<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildSquadSnapshot;
use App\Domain\Models\Week;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\SquadSnapshotFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildSquadSnapshotTest extends TestCase
{
    /**
     * @return BuildSquadSnapshot
     */
    protected function getDomainAction()
    {
        return app(BuildSquadSnapshot::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_is_an_existing_snapshot_for_the_squad_and_week()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        $squad = SquadFactory::new()->create();
        $existingSnapshot = SquadSnapshotFactory::new()->withSquadID($squad->id)->withWeekID($week->id)->create();

        try {
            $this->getDomainAction()->execute($squad, $week);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildSquadSnapshot::EXCEPTION_CODE_SNAPSHOT_EXISTS, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }
}
