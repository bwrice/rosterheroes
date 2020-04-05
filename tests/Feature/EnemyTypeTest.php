<?php

namespace Tests\Feature;

use App\Domain\Models\EnemyType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnemyTypeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_has_an_enemy_type_behavior()
    {
        $enemyTypes = EnemyType::all();
        $enemyTypes->each(function (EnemyType $enemyType) {
            try {
                $behavior = $enemyType->getBehavior();
                $this->assertNotNull($behavior);
            } catch (\Exception $exception) {
                $this->fail("no behavior for " . $enemyType->name);
            }
        });
    }
}
