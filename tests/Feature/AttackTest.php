<?php

namespace Tests\Feature;

use App\Domain\Models\Attack;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;

class AttackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function each_attack_has_config_attributes()
    {
        $attacks = Attack::all();
        $attacks->each(function (Attack $attack) {
            try {
                $this->assertNotNull($attack->getInitialSpeed());
                $this->assertNotNull($attack->getInitialBaseDamage());
                $this->assertNotNull($attack->getInitialDamageMultiplier());
                $this->assertNotNull($attack->getResourceCosts());
                $this->assertNotNull($attack->getRequirements());
            } catch (\Exception $exception) {
                $this->fail("Failed to get config attribute for attack: "
                    . $attack->name . ' with exception ' . $exception->getMessage());
            }
        });
    }
}
