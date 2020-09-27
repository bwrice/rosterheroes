<?php

namespace Tests\Feature;

use App\Domain\Actions\ConvertHeroSnapshotToCombatHero;
use App\Domain\Actions\ConvertSquadSnapshotIntoCombatSquad;
use App\Domain\Models\HeroSnapshot;
use App\Factories\Models\HeroSnapshotFactory;
use App\Factories\Models\SquadSnapshotFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertSquadSnapshotIntoCombatSquadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return ConvertSquadSnapshotIntoCombatSquad
     */
    protected function getDomainAction()
    {
        return app(ConvertSquadSnapshotIntoCombatSquad::class);
    }

    /**
     * @test
     */
    public function it_will_create_a_combat_squad_with_matching_properties_of_the_squad_snapshot()
    {
        $squadSnapshot = SquadSnapshotFactory::new()->create();
        $combatSquad = $this->getDomainAction()->execute($squadSnapshot);

        $this->assertEquals($squadSnapshot->uuid, $combatSquad->getSourceUuid());
        $this->assertEquals($squadSnapshot->experience, $combatSquad->getExperience());
        $this->assertEquals($squadSnapshot->squad_rank_id, $combatSquad->getSquadRankID());
    }

    /**
     * @test
     */
    public function it_will_execute_convert_combat_hero_on_each_hero_snapshot_belonging_to_the_squad_snapshot()
    {
        $squadSnapshot = SquadSnapshotFactory::new()->create();

        $heroSnapshots = collect();
        $heroSnapshotFactory = HeroSnapshotFactory::new()->forSquadSnapshot($squadSnapshot);
        for ($i = 1; $i <= rand(2, 4); $i++) {
            $heroSnapshots->push($heroSnapshotFactory->create());
        }

        $this->assertGreaterThan(1, $heroSnapshots->count());

        $heroSnapshotUuids = $heroSnapshots->map(function (HeroSnapshot $heroSnapshot) {
            return $heroSnapshot->uuid;
        });

        $convertCombatHeroMock = $this->getMockBuilder(ConvertHeroSnapshotToCombatHero::class)
            ->disableOriginalConstructor()
            ->getMock();

        $convertCombatHeroMock->expects($this->exactly($heroSnapshots->count()))
            ->method('execute')
            ->with($this->callback(function (HeroSnapshot $heroSnapshot) use ($heroSnapshotUuids) {
                $matchingKey = $heroSnapshotUuids->search($heroSnapshot->uuid);
                if ($matchingKey === false) {
                    return false;
                }
                $heroSnapshotUuids->forget($matchingKey);
                return true;
            }))
            ->willReturn('anything');

        $this->instance(ConvertHeroSnapshotToCombatHero::class, $convertCombatHeroMock);

        $combatSquad = $this->getDomainAction()->execute($squadSnapshot);
        $this->assertEquals($heroSnapshots->count(), $combatSquad->getCombatHeroes()->count());
    }
}
