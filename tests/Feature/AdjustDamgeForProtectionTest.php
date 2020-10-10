<?php

namespace Tests\Feature;

use App\Domain\Combat\Attacks\AdjustDamageForProtection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdjustDamageForProtectionTest extends TestCase
{
    /**
     * @return AdjustDamageForProtection
     */
    protected function getDomainAction()
    {
        return app(AdjustDamageForProtection::class);
    }

    /**
     * @test
     */
    public function armor_equal_to_base_factor_will_reduce_damage_by_50_percent()
    {
        $armor = AdjustDamageForProtection::BASE_FACTOR;

        $initialDamage = 1000;
        $adjustedDamage = $this->getDomainAction()->execute($initialDamage, $armor);
        $this->assertEquals(500, $adjustedDamage);
    }

    /**
     * @test
     */
    public function armor_equal_to_triple_the_base_factory_will_reduce_armor_by_75_percent()
    {
        $armor = 3 * AdjustDamageForProtection::BASE_FACTOR;

        $initialDamage = 1000;
        $adjustedDamage = $this->getDomainAction()->execute($initialDamage, $armor);
        $this->assertEquals(250, $adjustedDamage);
    }
}
