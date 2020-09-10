<?php

namespace Tests\Feature;

use App\AttackSnapshot;
use App\Domain\Actions\BuildHeroSnapshot;
use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Actions\CalculateHeroFantasyPower;
use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\Spell;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\SquadSnapshotFactory;
use App\MeasurableSnapshot;
use App\Nova\PlayerSpirit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildHeroSnapshotTest extends TestCase
{
//    use DatabaseTransactions;

    /** @var Week */
    protected $currentWeek;

    /** @var SquadSnapshot */
    protected $squadSnapshot;

    /** @var Hero */
    protected $hero;

    public function setUp(): void
    {
        parent::setUp();

        $this->currentWeek = factory(Week::class)->states('as-current')->create();
        $this->squadSnapshot = SquadSnapshotFactory::new()->withWeekID($this->currentWeek->id)->create();
        $playerGameLogFactory = PlayerGameLogFactory::new()->withStats();
        $playerSpiritFactory = PlayerSpiritFactory::new()->forWeek($this->currentWeek)->withPlayerGameLog($playerGameLogFactory);
        $this->hero = HeroFactory::new()
            ->withSquadID($this->squadSnapshot->squad_id)
            ->withPlayerSpirit($playerSpiritFactory)
            ->beginnerWarrior()
            ->create();

        Date::setTestNow(WeekService::finalizingStartsAt($this->currentWeek->adventuring_locks_at)->addHour());
    }


    /**
     * @return BuildHeroSnapshot
     */
    protected function getDomainAction()
    {
        return app(BuildHeroSnapshot::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_current_week_is_not_the_squad_snapshot_week()
    {
        $newCurrentWeek = factory(Week::class)->states('as-current')->create();

        try {
            $this->getDomainAction()->execute($this->squadSnapshot, $this->hero);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildHeroSnapshot::EXCEPTION_CODE_SNAPSHOT_WEEK_NOT_CURRENT, $exception->getCode());
            return;
        }

        $this->fail("exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_building_a_hero_snapshot_when_week_not_finalizing()
    {
        Date::setTestNow(WeekService::finalizingStartsAt($this->currentWeek->adventuring_locks_at)->subHour());

        try {
            $this->getDomainAction()->execute($this->squadSnapshot, $this->hero);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildHeroSnapshot::EXCEPTION_CODE_WEEK_NOT_FINALIZING, $exception->getCode());
            return;
        }

        $this->fail("exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_hero_does_belong_to_the_squad_snapshots_squad()
    {
        $misMatchedHero = HeroFactory::new()->create();

        try {
            $this->getDomainAction()->execute($this->squadSnapshot, $misMatchedHero);
        } catch (\Exception $exception) {
            $this->assertEquals(BuildHeroSnapshot::EXCEPTION_CODE_SNAPSHOT_MISMATCH, $exception->getCode());
            return;
        }

        $this->fail("exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_create_a_hero_snapshot_matching_a_hero_expected_properties()
    {
        $heroSnapshot = $this->getDomainAction()->execute($this->squadSnapshot, $this->hero);
        $this->assertEquals($this->squadSnapshot->id, $heroSnapshot->squad_snapshot_id);
        $this->assertEquals($this->hero->id, $heroSnapshot->hero_id);

        $this->assertNotNull($this->hero->player_spirit_id);
        $this->assertEquals($this->hero->player_spirit_id, $heroSnapshot->player_spirit_id);

        $this->assertEquals($this->hero->combat_position_id, $heroSnapshot->combat_position_id);

        $this->assertEquals($this->hero->getProtection(), $heroSnapshot->protection);

        $this->assertTrue(abs($this->hero->getBlockChance() - $heroSnapshot->block_chance) < 0.01);

        /** @var CalculateHeroFantasyPower $calculateFantasyPower */
        $calculateFantasyPower = app(CalculateHeroFantasyPower::class);
        $fantasyPower = $calculateFantasyPower->execute($this->hero);
        $this->assertTrue(abs($fantasyPower - $heroSnapshot->fantasy_power) < 0.01);
    }

    /**
     * @test
     */
    public function it_will_create_measurable_snapshots_for_the_hero_snapshot_that_match_the_hero_measurables()
    {
        $heroSnapshot = $this->getDomainAction()->execute($this->squadSnapshot, $this->hero);
        $measurableSnapshots = $heroSnapshot->measurableSnapshots()->with('measurable')->get();
        $measurableTypes = MeasurableType::all();

        $heroMeasurables = $this->hero->measurables;
        $measurableTypes->each(function (MeasurableType $measurableType) use ($measurableSnapshots, $heroMeasurables) {
            /** @var MeasurableSnapshot $matchingMeasurableSnapshot */
            $matchingMeasurableSnapshot = $measurableSnapshots->first(function (MeasurableSnapshot $measurableSnapshot) use ($measurableType) {
                return $measurableSnapshot->measurable->measurable_type_id === $measurableType->id;
            });
            $this->assertNotNull($matchingMeasurableSnapshot, "No snapshot for type: ". $measurableType->name);
            /** @var Measurable $matchingHeroMeasurable */
            $matchingHeroMeasurable = $heroMeasurables->first(function (Measurable $measurable) use ($measurableType) {
                return $measurable->measurable_type_id === $measurableType->id;
            });
            $this->assertNotNull($matchingHeroMeasurable, "No hero measurable for type: " . $measurableType->name);
            $this->assertEquals($matchingHeroMeasurable->getPreBuffedAmount(), $matchingMeasurableSnapshot->pre_buffed_amount);
            $this->assertEquals($matchingHeroMeasurable->getBuffedAmount(), $matchingMeasurableSnapshot->buffed_amount);
            $this->assertEquals($matchingHeroMeasurable->getCurrentAmount(), $matchingMeasurableSnapshot->final_amount);
        });
    }

    /**
     * @test
     */
    public function it_will_create_attack_snapshots_for_the_hero_snapshot_matching_the_attacks_of_the_hero()
    {
        $heroSnapshot = $this->getDomainAction()->execute($this->squadSnapshot, $this->hero);

        $heroAttacks = $this->hero->items->getAttacks();

        $this->assertTrue($heroAttacks->isNotEmpty());
        $this->assertEquals($heroAttacks->count(), $heroSnapshot->attackSnapshots->count());

        /** @var CalculateHeroFantasyPower $calculateFantasyPower */
        $calculateFantasyPower = app(CalculateHeroFantasyPower::class);
        $fantasyPower = $calculateFantasyPower->execute($this->hero);
        $this->assertGreaterThan(1, $fantasyPower);

        /** @var CalculateCombatDamage $calculateDamage */
        $damageCalculator = app(CalculateCombatDamage::class);
        $heroSnapshot->attackSnapshots->each(function (AttackSnapshot $attackSnapshot) use ($heroAttacks, $fantasyPower, $damageCalculator) {
            /** @var Attack $matchingAttack */
            $matchingAttack = $heroAttacks->first(function (Attack $attack) use ($attackSnapshot) {
                return $attack->id === $attackSnapshot->attack_id;
            });

            $this->assertEquals($matchingAttack->name, $attackSnapshot->name);
            $this->assertEquals($matchingAttack->attacker_position_id, $attackSnapshot->attacker_position_id);
            $this->assertEquals($matchingAttack->target_priority_id, $attackSnapshot->target_priority_id);
            $this->assertEquals($matchingAttack->damage_type_id, $attackSnapshot->damage_type_id);
            $this->assertEquals($matchingAttack->target_priority_id, $attackSnapshot->target_priority_id);
            $this->assertEquals($matchingAttack->tier, $attackSnapshot->tier);
            $this->assertEquals($matchingAttack->targets_count, $attackSnapshot->targets_count);

            $this->assertNotNull($matchingAttack);
            $this->assertTrue(abs($matchingAttack->getCombatSpeed() - $attackSnapshot->combat_speed) < 0.01);

            $damage = $damageCalculator->execute($matchingAttack, $fantasyPower);
            $this->assertEquals($damage, $attackSnapshot->damage);
        });
    }

    /**
     * @test
     */
    public function the_hero_snapshot_will_belong_to_items_the_hero_has_equipped_at_the_time()
    {
        $heroSnapshot = $this->getDomainAction()->execute($this->squadSnapshot, $this->hero);

        $heroItems = $this->hero->items;
        $this->assertTrue($heroItems->isNotEmpty());

        $heroSnapshotItems = $heroSnapshot->items;
        $this->assertTrue($heroSnapshotItems->isNotEmpty());

        $this->assertEquals($heroItems->count(), $heroSnapshotItems->count());
        $this->assertEquals($heroItems->sortBy('id')->pluck('id')->values()->toArray(), $heroSnapshotItems->sortBy('id')->pluck('id')->values()->toArray());
    }

    /**
     * @test
     */
    public function the_hero_snapshot_will_belong_to_spells_that_the_hero_had_casted()
    {
        $spellsToAttach = Spell::query()->inRandomOrder()->take(rand(1, 4))->get();
        $this->hero->spells()->saveMany($spellsToAttach);
        $this->hero = $this->hero->fresh();
        $heroSnapshot = $this->getDomainAction()->execute($this->squadSnapshot, $this->hero);

        $heroSpells = $this->hero->spells;
        $this->assertTrue($heroSpells->isNotEmpty());

        $heroSnapshotSpells = $heroSnapshot->spells;
        $this->assertTrue($heroSnapshotSpells->isNotEmpty());

        $this->assertEquals($heroSpells->count(), $heroSnapshotSpells->count());
        $this->assertEquals($heroSpells->sortBy('id')->pluck('id')->values()->toArray(), $heroSnapshotSpells->sortBy('id')->pluck('id')->values()->toArray());
    }

}
