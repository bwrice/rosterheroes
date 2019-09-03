<?php

namespace Tests\Feature;

use App\Domain\Behaviors\ItemBases\Weapons\SwordBehavior;
use App\Domain\Models\Attack;
use App\Domain\Models\DamageType;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\TargetRange;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttackUnitTest extends TestCase
{
    /** @var Attack */
    protected $attack;

    /** @var Item */
    protected $sword;

    public function setUp(): void
    {
        parent::setUp();

        $this->attack = factory(Attack::class)->create();
    }

    /**
     * @test
     */
    public function attack_speed_is_correctly_adjusted_for_damage_types()
    {
        $damageTypesTested = 0;

        $this->attack->damage_type_id = DamageType::forName(DamageType::SINGLE_TARGET)->id;
        $this->attack->save();
        $singleTargetSpeed = $this->attack->fresh()->getCombatSpeed();
        $damageTypesTested++;

        $this->attack->damage_type_id = DamageType::forName(DamageType::MULTI_TARGET)->id;
        $this->attack->save();
        $multiTargetSpeed = $this->attack->fresh()->getCombatSpeed();
        $damageTypesTested++;

        $this->assertGreaterThan($multiTargetSpeed, $singleTargetSpeed);

        $this->attack->damage_type_id = DamageType::forName(DamageType::DISPERSED)->id;
        $this->attack->save();
        $dispersedSpeed = $this->attack->fresh()->getCombatSpeed();
        $damageTypesTested++;

        $this->assertGreaterThan($dispersedSpeed, $multiTargetSpeed);

        $this->attack->damage_type_id = DamageType::forName(DamageType::AREA_OF_EFFECT)->id;
        $this->attack->save();
        $aoeSpeed = $this->attack->fresh()->getCombatSpeed();
        $damageTypesTested++;

        $this->assertGreaterThan($aoeSpeed, $dispersedSpeed);

        $this->assertEquals(DamageType::all()->count(), $damageTypesTested, "All damage types tested");
    }

    /**
     * @test
     */
    public function attack_speed_is_correctly_adjusted_for_target_ranges()
    {
        $damageTypesTested = 0;

        $this->attack->target_range_id = TargetRange::forName(TargetRange::MELEE)->id;
        $this->attack->save();
        $meleeTargetRange = $this->attack->fresh()->getCombatSpeed();
        $damageTypesTested++;

        $this->attack->target_range_id = TargetRange::forName(TargetRange::MID_RANGE)->id;
        $this->attack->save();
        $midRangeSpeed = $this->attack->fresh()->getCombatSpeed();
        $damageTypesTested++;

        $this->assertGreaterThan($midRangeSpeed, $meleeTargetRange);

        $this->attack->target_range_id = TargetRange::forName(TargetRange::LONG_RANGE)->id;
        $this->attack->save();
        $longRangeSpeed = $this->attack->fresh()->getCombatSpeed();
        $damageTypesTested++;

        $this->assertGreaterThan($longRangeSpeed, $midRangeSpeed);

        $this->assertEquals(TargetRange::all()->count(), $damageTypesTested, "All target ranges tested");
    }
}
