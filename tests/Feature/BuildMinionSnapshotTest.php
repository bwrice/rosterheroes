<?php

namespace Tests\Feature;

use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Actions\Snapshots\BuildMinionSnapshot;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Facades\WeekService;
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
    public function building_a_minion_snapshot_will_throw_an_exception_if_the_week_given_is_not_the_current_week()
    {
        $minion = MinionFactory::new()->create();
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->state('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        $diffWeek = factory(Week::class)->create();

        try {
           $this->getDomainAction()->execute($minion, $diffWeek);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildMinionSnapshot::EXCEPTION_CODE_INVALID_WEEK, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function building_a_minion_snapshot_will_throw_an_exception_if_the_current_week_is_not_finalizing()
    {
        $minion = MinionFactory::new()->create();
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->state('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->subHour());

        try {
            $this->getDomainAction()->execute($minion, $currentWeek);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildMinionSnapshot::EXCEPTION_CODE_WEEK_NOT_FINALIZING, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
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

        $minionSnapshot = $this->getDomainAction()->execute($minion, $currentWeek);

        $this->assertEquals($currentWeek->id, $minionSnapshot->week_id);
        $this->assertEquals($minion->combat_position_id, $minionSnapshot->combat_position_id);
        $this->assertEquals($minion->enemy_type_id, $minionSnapshot->enemy_type_id);
        $this->assertEquals($minion->level, $minionSnapshot->level);
        $this->assertEquals($minion->getStartingHealth(), $minionSnapshot->starting_health);
        $this->assertEquals($minion->getProtection(), $minionSnapshot->protection);
        $this->assertEquals($minion->getExperienceReward(), $minionSnapshot->experience_reward);
        $this->assertEquals($minion->getFavorReward(), $minionSnapshot->favor_reward);

        $this->assertTrue(abs($minion->getBlockChance() - $minionSnapshot->block_chance) < 0.01);

        /** @var CalculateFantasyPower $calculateFantasyPower */
        $calculateFantasyPower = app(CalculateFantasyPower::class);
        $fantasyPower = $calculateFantasyPower->execute($minion->getFantasyPoints());
        $this->assertTrue(abs($fantasyPower - $minionSnapshot->fantasy_power) < 0.01);
    }
}
