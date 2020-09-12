<?php

namespace Tests\Feature;

use App\AttackSnapshot;
use App\Domain\Actions\BuildAttackSnapshot;
use App\Domain\Actions\BuildHeroSnapshot;
use App\Domain\Actions\CalculateFantasyPower;
use App\Domain\Actions\CalculateHeroFantasyPower;
use App\Domain\Actions\Combat\CalculateCombatDamage;
use App\Domain\Models\Attack;
use App\Domain\Models\Hero;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemType;
use App\Domain\Models\Measurable;
use App\Domain\Models\MeasurableType;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\Spell;
use App\Domain\Models\SquadSnapshot;
use App\Domain\Models\Week;
use App\Facades\WeekService;
use App\Factories\Models\HeroFactory;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\SquadSnapshotFactory;
use App\HeroSnapshot;
use App\MeasurableSnapshot;
use App\Nova\PlayerSpirit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class BuildHeroSnapshotTest extends TestCase
{
    use DatabaseTransactions;

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
        $diffWeek = factory(Week::class)->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($diffWeek->id)->create();
        $hero = HeroFactory::new()->withSquadID($squadSnapshot->squad_id)->create();

        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        try {
            $this->getDomainAction()->execute($squadSnapshot, $hero);
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
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withSquadID($squadSnapshot->squad_id)->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->subHour());

        try {
            $this->getDomainAction()->execute($squadSnapshot, $hero);
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
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withSquadID(SquadFactory::new()->create()->id)->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        try {
            $this->getDomainAction()->execute($squadSnapshot, $hero);
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
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withSquadID($squadSnapshot->squad_id)->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        $heroSnapshot = $this->getDomainAction()->execute($squadSnapshot, $hero);

        $this->assertEquals($squadSnapshot->id, $heroSnapshot->squad_snapshot_id);
        $this->assertEquals($hero->id, $heroSnapshot->hero_id);


        $this->assertEquals($hero->combat_position_id, $heroSnapshot->combat_position_id);

        $this->assertEquals($hero->getProtection(), $heroSnapshot->protection);

        $this->assertTrue(abs($hero->getBlockChance() - $heroSnapshot->block_chance) < 0.01);
    }

    /**
     * @test
     */
    public function it_will_create_a_hero_snapshot_with_zero_fantasy_power_for_a_hero_without_a_player_spirit()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withSquadID($squadSnapshot->squad_id)->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        $heroSnapshot = $this->getDomainAction()->execute($squadSnapshot, $hero);
        $this->assertNull($heroSnapshot->player_spirit_id);
        $this->assertEquals(0, $heroSnapshot->fantasy_power);
    }

    /**
     * @test
     */
    public function it_will_create_a_hero_snapshot_with_the_expected_fantasy_power_of_the_player_spirit()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withSquadID($squadSnapshot->squad_id)
            ->withMeasurables()
            ->withPlayerSpirit(PlayerSpiritFactory::new()
                ->withPlayerGameLog(PlayerGameLogFactory::new()->withStats()))
            ->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        /** @var CalculateHeroFantasyPower $calculateFantasyPower */
        $calculateFantasyPower = app(CalculateHeroFantasyPower::class);
        $fantasyPower = $calculateFantasyPower->execute($hero);
        $this->assertGreaterThan(0, $fantasyPower);

        $heroSnapshot = $this->getDomainAction()->execute($squadSnapshot, $hero);
        $this->assertEquals($hero->player_spirit_id, $heroSnapshot->player_spirit_id);
        $this->assertTrue(abs(round($fantasyPower, 2) - $heroSnapshot->fantasy_power) < PHP_FLOAT_EPSILON);
    }

    /**
     * @test
     */
    public function it_will_create_measurable_snapshots_for_the_hero_snapshot_that_match_the_hero_measurables()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withSquadID($squadSnapshot->squad_id)->withMeasurables()->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());
        $heroSnapshot = $this->getDomainAction()->execute($squadSnapshot, $hero);

        $measurableSnapshots = $heroSnapshot->measurableSnapshots()->with('measurable')->get();
        $measurableTypes = MeasurableType::all();

        $heroMeasurables = $hero->measurables;
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
    public function it_will_execute_create_attack_snapshot_for_each_hero_attack()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withSquadID($squadSnapshot->squad_id)
            ->withMeasurables()
            ->withPlayerSpirit(PlayerSpiritFactory::new()
                ->withPlayerGameLog(PlayerGameLogFactory::new()->withStats()))
            ->create();

        /** @var ItemType $itemType */
        $itemType = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            $builder->whereIn('name', [
                ItemBase::TWO_HAND_AXE,
                ItemBase::BOW,
                ItemBase::WAND
            ]);
        })->inRandomOrder()->first();

        $item = ItemFactory::new()->withItemType($itemType)->create();
        $item->hasItems()->associate($hero);
        $item->save();

        $heroAttacks = $hero->fresh()->getAttacks();
        $this->assertTrue($heroAttacks->isNotEmpty());

        $heroAttackIDs = $heroAttacks->pluck('id')->values();

        $mock = $this->getMockBuilder(BuildAttackSnapshot::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->exactly($heroAttacks->count()))->method('execute')->with($this->callback(function (Attack $attack) use ($heroAttackIDs) {

            $matchingKey = $heroAttackIDs->search($attack->id);
            if ($matchingKey === false) {
                return false;
            }
            $heroAttackIDs->forget($matchingKey);
            return true;

        }), $this->callback(function (HeroSnapshot $heroSnapshot) use ($hero) {
            return $heroSnapshot->hero_id === $hero->id;
        }));

        $this->instance(BuildAttackSnapshot::class, $mock);

        $this->getDomainAction()->execute($squadSnapshot, $hero);

//        $heroAttacks = $hero->items->getAttacks();
//
//        $this->assertTrue($heroAttacks->isNotEmpty());
//        $this->assertEquals($heroAttacks->count(), $heroSnapshot->attackSnapshots->count());
//
//        /** @var CalculateHeroFantasyPower $calculateFantasyPower */
//        $calculateFantasyPower = app(CalculateHeroFantasyPower::class);
//        $fantasyPower = $calculateFantasyPower->execute($hero);
//        $this->assertGreaterThan(1, $fantasyPower);
//
//        /** @var CalculateCombatDamage $calculateDamage */
//        $damageCalculator = app(CalculateCombatDamage::class);
//        $heroSnapshot->attackSnapshots->each(function (AttackSnapshot $attackSnapshot) use ($heroAttacks, $fantasyPower, $damageCalculator) {
//            /** @var Attack $matchingAttack */
//            $matchingAttack = $heroAttacks->first(function (Attack $attack) use ($attackSnapshot) {
//                return $attack->id === $attackSnapshot->attack_id;
//            });
//
//            $this->assertEquals($matchingAttack->name, $attackSnapshot->name);
//            $this->assertEquals($matchingAttack->attacker_position_id, $attackSnapshot->attacker_position_id);
//            $this->assertEquals($matchingAttack->target_priority_id, $attackSnapshot->target_priority_id);
//            $this->assertEquals($matchingAttack->damage_type_id, $attackSnapshot->damage_type_id);
//            $this->assertEquals($matchingAttack->target_priority_id, $attackSnapshot->target_priority_id);
//            $this->assertEquals($matchingAttack->tier, $attackSnapshot->tier);
//            $this->assertEquals($matchingAttack->targets_count, $attackSnapshot->targets_count);
//
//            $this->assertNotNull($matchingAttack);
//            $this->assertTrue(abs($matchingAttack->getCombatSpeed() - $attackSnapshot->combat_speed) < 0.01);
//
//            $damage = $damageCalculator->execute($matchingAttack, $fantasyPower);
//            $this->assertEquals($damage, $attackSnapshot->damage);
//        });
    }

    /**
     * @test
     */
    public function the_hero_snapshot_will_belong_to_items_the_hero_has_equipped_at_the_time()
    {
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withMeasurables()->withSquadID($squadSnapshot->squad_id)->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        // Get item types without attacks so we dont have to mock building attack snapshots
        $itemTypes = ItemType::query()->whereHas('itemBase', function (Builder $builder) {
            $builder->whereIn('name', [
                ItemBase::HELMET,
                ItemBase::BOOTS,
                ItemBase::HEAVY_ARMOR,
                ItemBase::RING,
                ItemBase::BELT,
                ItemBase::BRACELET,
                ItemBase::SHIELD,
                ItemBase::NECKLACE,
                ItemBase::LEGGINGS
            ]);
        })->inRandomOrder()->take(rand(2, 4));

        $itemTypes->each(function (ItemType $itemType) use ($hero) {

            $item = ItemFactory::new()->withItemType($itemType)->create();
            $item->hasItems()->associate($hero);
            $item->save();
        });


        $heroSnapshot = $this->getDomainAction()->execute($squadSnapshot, $hero);

        $heroItems = $hero->items;
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
        /** @var Week $currentWeek */
        $currentWeek = factory(Week::class)->states('as-current')->create();
        $squadSnapshot = SquadSnapshotFactory::new()->withWeekID($currentWeek->id)->create();
        $hero = HeroFactory::new()->withMeasurables()->withSquadID($squadSnapshot->squad_id)->create();

        Date::setTestNow(WeekService::finalizingStartsAt($currentWeek->adventuring_locks_at)->addHour());

        $spellsToAttach = Spell::query()->inRandomOrder()->take(rand(1, 4))->get();
        $hero->spells()->saveMany($spellsToAttach);
        $hero = $hero->fresh();
        $heroSnapshot = $this->getDomainAction()->execute($squadSnapshot, $hero);

        $heroSpells = $hero->spells;
        $this->assertTrue($heroSpells->isNotEmpty());

        $heroSnapshotSpells = $heroSnapshot->spells;
        $this->assertTrue($heroSnapshotSpells->isNotEmpty());

        $this->assertEquals($heroSpells->count(), $heroSnapshotSpells->count());
        $this->assertEquals($heroSpells->sortBy('id')->pluck('id')->values()->toArray(), $heroSnapshotSpells->sortBy('id')->pluck('id')->values()->toArray());
    }

}
