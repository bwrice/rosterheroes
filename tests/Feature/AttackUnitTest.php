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
        $targetRangesTested = 0;

        $this->attack->target_range_id = TargetRange::forName(TargetRange::MELEE)->id;
        $this->attack->save();
        $meleeSpeed = $this->attack->fresh()->getCombatSpeed();
        $targetRangesTested++;

        $this->attack->target_range_id = TargetRange::forName(TargetRange::MID_RANGE)->id;
        $this->attack->save();
        $midRangeSpeed = $this->attack->fresh()->getCombatSpeed();
        $targetRangesTested++;

        $this->assertGreaterThan($midRangeSpeed, $meleeSpeed);

        $this->attack->target_range_id = TargetRange::forName(TargetRange::LONG_RANGE)->id;
        $this->attack->save();
        $longRangeSpeed = $this->attack->fresh()->getCombatSpeed();
        $targetRangesTested++;

        $this->assertGreaterThan($longRangeSpeed, $midRangeSpeed);

        $this->assertEquals(TargetRange::all()->count(), $targetRangesTested, "All target ranges tested");
    }

    /**
     * @test
     */
    public function attack_base_damage_is_correctly_adjusted_for_damage_types()
    {
        $damageTypesTested = 0;

        $this->attack->damage_type_id = DamageType::forName(DamageType::DISPERSED)->id;
        $this->attack->save();
        $dispersedBaseDamage = $this->attack->fresh()->getBaseDamage();
        $damageTypesTested++;

        $this->attack->damage_type_id = DamageType::forName(DamageType::SINGLE_TARGET)->id;
        $this->attack->save();
        $singleTargetBaseDamage = $this->attack->fresh()->getBaseDamage();
        $damageTypesTested++;

        $this->assertGreaterThan($singleTargetBaseDamage, $dispersedBaseDamage);

        $this->attack->damage_type_id = DamageType::forName(DamageType::MULTI_TARGET)->id;
        $this->attack->save();
        $multiTargetBaseDamage = $this->attack->fresh()->getBaseDamage();
        $damageTypesTested++;

        $this->assertGreaterThan($multiTargetBaseDamage, $singleTargetBaseDamage);

        $this->attack->damage_type_id = DamageType::forName(DamageType::AREA_OF_EFFECT)->id;
        $this->attack->save();
        $aoeBaseDamage = $this->attack->fresh()->getBaseDamage();
        $damageTypesTested++;

        $this->assertGreaterThan($aoeBaseDamage, $multiTargetBaseDamage);

        $this->assertEquals(DamageType::all()->count(), $damageTypesTested, "All damage types tested");
    }

    /**
     * @test
     */
    public function attack_base_damage_is_correctly_adjusted_for_target_ranges()
    {
        $targetRangesTested = 0;

        $this->attack->target_range_id = TargetRange::forName(TargetRange::MELEE)->id;
        $this->attack->save();
        $meleeBaseDamage = $this->attack->fresh()->getBaseDamage();
        $targetRangesTested++;

        $this->attack->target_range_id = TargetRange::forName(TargetRange::MID_RANGE)->id;
        $this->attack->save();
        $midRangeBaseDamage = $this->attack->fresh()->getBaseDamage();
        $targetRangesTested++;

        $this->assertGreaterThan($midRangeBaseDamage, $meleeBaseDamage);

        $this->attack->target_range_id = TargetRange::forName(TargetRange::LONG_RANGE)->id;
        $this->attack->save();
        $longRangeBaseDamage = $this->attack->fresh()->getBaseDamage();
        $targetRangesTested++;

        $this->assertGreaterThan($longRangeBaseDamage, $midRangeBaseDamage);

        $this->assertEquals(TargetRange::all()->count(), $targetRangesTested, "All target ranges tested");
    }

    /**
     * @test
     */
    public function attack_damage_modifier_is_correctly_adjusted_for_damage_types()
    {
        $damageTypesTested = 0;

        $this->attack->damage_type_id = DamageType::forName(DamageType::DISPERSED)->id;
        $this->attack->save();
        $dispersedDamageModifier = $this->attack->fresh()->getDamageModifier();
        $damageTypesTested++;

        $this->attack->damage_type_id = DamageType::forName(DamageType::SINGLE_TARGET)->id;
        $this->attack->save();
        $singleTargetDamageModifier = $this->attack->fresh()->getDamageModifier();
        $damageTypesTested++;

        $this->assertGreaterThan($singleTargetDamageModifier, $dispersedDamageModifier);

        $this->attack->damage_type_id = DamageType::forName(DamageType::MULTI_TARGET)->id;
        $this->attack->save();
        $multiTargetDamageModifier = $this->attack->fresh()->getDamageModifier();
        $damageTypesTested++;

        $this->assertGreaterThan($multiTargetDamageModifier, $singleTargetDamageModifier);

        $this->attack->damage_type_id = DamageType::forName(DamageType::AREA_OF_EFFECT)->id;
        $this->attack->save();
        $aoeDamageModifier = $this->attack->fresh()->getDamageModifier();
        $damageTypesTested++;

        $this->assertGreaterThan($aoeDamageModifier, $multiTargetDamageModifier);

        $this->assertEquals(DamageType::all()->count(), $damageTypesTested, "All damage types tested");
    }
}
