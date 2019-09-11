<?php

namespace Tests\Unit;

use App\Domain\Behaviors\ItemBases\Weapons\AxeBehavior;
use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\MeasurableType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeaponBehaviorUnitTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     * @dataProvider provides_certain_weapons_have_more_base_damage_with_more_valor
     * @param $weaponBehaviorClass
     */
    public function certain_weapons_have_more_base_damage_with_more_valor($weaponBehaviorClass)
    {
        /** @var WeaponBehavior $weaponBehavior */
        $weaponBehavior = app($weaponBehaviorClass);

        $lowValor = \Mockery::mock(HasItems::class);
        $lowValor->shouldReceive('getValorAmount')->andReturn(10);

        $highValor = \Mockery::mock(HasItems::class);
        $highValor->shouldReceive('getValorAmount')->andReturn(99);

        $lowValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($lowValor);
        $highValorBaseDamageModifier = $weaponBehavior->getBaseDamageModifier($highValor);

        $this->assertGreaterThan($lowValorBaseDamageModifier, $highValorBaseDamageModifier);
    }

    public function provides_certain_weapons_have_more_base_damage_with_more_valor()
    {
        return [
            ItemBase::AXE => [
                'weaponBehaviorClass' => AxeBehavior::class
            ]
        ];
    }
}
