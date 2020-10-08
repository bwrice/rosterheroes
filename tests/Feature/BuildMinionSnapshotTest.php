<?php

namespace Tests\Feature;

use App\Domain\Actions\Snapshots\BuildAttackSnapshot;
use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Actions\Snapshots\BuildMinionSnapshot;
use App\Domain\Models\Attack;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\MinionSnapshot;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use App\Factories\Models\ChestBlueprintFactory;
use App\Factories\Models\MinionFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildMinionSnapshotTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuildMinionSnapshot
     */
    protected function getDomainAction()
    {
        return app(BuildMinionSnapshot::class);
    }

    /**
     * @test
     */
    public function it_will_build_a_minion_snapshot_for_the_current_week()
    {
        $minion = MinionFactory::new()->create();
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->state('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        /** @var MinionSnapshot $minionSnapshot */
        $minionSnapshot = $this->getDomainAction()->execute($minion, $currentWeek);

        $this->assertEquals($currentWeek->id, $minionSnapshot->week_id);
        $this->assertEquals($minion->combat_position_id, $minionSnapshot->combat_position_id);
        $this->assertEquals($minion->enemy_type_id, $minionSnapshot->enemy_type_id);
        $this->assertEquals($minion->level, $minionSnapshot->level);
        $this->assertEquals($minion->getStartingHealth(), $minionSnapshot->starting_health);
        $this->assertEquals($minion->getStartingStamina(), $minionSnapshot->starting_stamina);
        $this->assertEquals($minion->getStartingMana(), $minionSnapshot->starting_mana);
        $this->assertEquals($minion->getProtection(), $minionSnapshot->protection);
        $this->assertEquals($minion->getExperienceReward(), $minionSnapshot->experience_reward);
        $this->assertEquals($minion->getFavorReward(), $minionSnapshot->favor_reward);

        $this->assertTrue(abs($minion->getBlockChance() - $minionSnapshot->block_chance) < 0.01);

        /** @var CalculateFantasyPower $calculateFantasyPower */
        $calculateFantasyPower = app(CalculateFantasyPower::class);
        $fantasyPower = $calculateFantasyPower->execute($minion->getFantasyPoints());
        $this->assertTrue(abs($fantasyPower - $minionSnapshot->fantasy_power) < 0.01);
    }

    /**
     * @test
     */
    public function it_will_execute_build_attack_snapshot_for_each_minion_attack()
    {
        $minion = MinionFactory::new()->withAttacks()->create();
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->state('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        $minionAttacks = $minion->attacks;
        $this->assertTrue($minionAttacks->isNotEmpty());

        $minionAttackIDs = $minionAttacks->pluck('id')->values();

        /** @var CalculateFantasyPower $calculateFantasyPower */
        $calculateFantasyPower = app(CalculateFantasyPower::class);
        $minionFantasyPower = $calculateFantasyPower->execute($minion->getFantasyPoints());

        $mock = $this->getMockBuilder(BuildAttackSnapshot::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->exactly($minionAttacks->count()))->method('execute')->with($this->callback(function (Attack $attack) use ($minionAttackIDs) {
            $matchingKey = $minionAttackIDs->search($attack->id);
            if ($matchingKey === false) {
                return false;
            }
            $minionAttackIDs->forget($matchingKey);
            return true;

        }), $this->callback(function (MinionSnapshot $minionSnapshot) use ($minion) {
            return $minionSnapshot->minion_id === $minion->id;
        }), $this->callback(function ($fantasyPower) use ($minionFantasyPower) {
            return abs($fantasyPower - $minionFantasyPower) < 0.01;
        }));

        app()->instance(BuildAttackSnapshot::class, $mock);

        $this->getDomainAction()->execute($minion, $currentWeek);
    }

    /**
     * @test
     */
    public function it_will_attach_chest_blueprints_of_the_minion_to_the_minion_snapshot()
    {
        $minion = MinionFactory::new()->create();

        for ($i = 1; $i <= rand(2, 4); $i++) {
            $chestBlueprint = ChestBlueprintFactory::new()->create();
            $minion->chestBlueprints()->save($chestBlueprint, [
                'chance' => round(rand(100, 10000)/100, 2),
                'count' => rand(1, 3)
            ]);
        }

        $minionChestBlueprints = $minion->chestBlueprints;
        $this->assertTrue($minionChestBlueprints->isNotEmpty());

        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->state('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        $minionSnapshot = $this->getDomainAction()->execute($minion, $currentWeek);

        $snapshotChestBlueprints = $minionSnapshot->chestBlueprints;
        $this->assertEquals($snapshotChestBlueprints->count(), $minionChestBlueprints->count());

        $snapshotChestBlueprints->each(function (ChestBlueprint $snapshotChestBlueprint) use ($minionChestBlueprints) {
            $matchingBlueprint = $minionChestBlueprints->first(function (ChestBlueprint $minionChestBlueprint) use ($snapshotChestBlueprint) {
                return $minionChestBlueprint->id === $snapshotChestBlueprint->id;
            });

            $this->assertNotNull($matchingBlueprint);
            $this->assertEquals($matchingBlueprint->pivot->count, $snapshotChestBlueprint->pivot->count);
            $this->assertTrue(abs($matchingBlueprint->pivot->chance - $snapshotChestBlueprint->pivot->chance) < 0.01);
        });
    }
}
