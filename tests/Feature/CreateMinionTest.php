<?php

namespace Tests\Feature;

use App\Domain\Actions\Content\CreateMinion;
use App\Domain\Models\Attack;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateMinionTest extends TestCase
{
    use DatabaseTransactions;

    protected $minionName;
    protected $enemyTypeName;
    protected $combatPositionName;
    protected $level;
    protected $attackNames;
    protected $chestBlueprintSeedArrays;

    public function setUp(): void
    {
        parent::setUp();
        $this->minionName = 'Test Minion' . Str::random(8);
        $this->enemyTypeName = EnemyType::query()->inRandomOrder()->first()->name;
        $this->combatPositionName = CombatPosition::query()->inRandomOrder()->first()->name;
        $this->level = rand(1, 50);
        $this->attackNames = Attack::query()->inRandomOrder()->take(rand(2,5))->pluck('name')->values()->toArray();
        $this->chestBlueprintSeedArrays = ChestBlueprint::query()
            ->inRandomOrder()->take(rand(2,5))->get()
            ->map(function (ChestBlueprint $chestBlueprint) {
                return [
                    'chest_blueprint' => $chestBlueprint->reference_id,
                    'count' => rand(1,3),
                    'chance' => rand(1, 100)
                ];
        })->values()->toArray();
    }

    /**
     * @return CreateMinion
     */
    protected function getDomainAction()
    {
        return app(CreateMinion::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_are_no_attacks()
    {
        try {

            $this->getDomainAction()->execute(
                $this->minionName,
                $this->level,
                $this->enemyTypeName,
                $this->combatPositionName,
                [],
                $this->chestBlueprintSeedArrays
            );
        } catch (\Exception $exception) {
            $minion = Minion::query()->where('name', '', $this->minionName)->first();
            $this->assertNull($minion);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_there_is_an_attack_not_found()
    {
        $attackNames = $this->attackNames;
        $attackNames[] = Str::random(10);
        try {
            $this->getDomainAction()->execute(
                $this->minionName,
                $this->level,
                $this->enemyTypeName,
                $this->combatPositionName,
                $attackNames,
                $this->chestBlueprintSeedArrays
            );
        } catch (\Exception $exception) {
            $minion = Minion::query()->where('name', '', $this->minionName)->first();
            $this->assertNull($minion);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_no_chest_seed_arrays()
    {
        try {
            $this->getDomainAction()->execute(
                $this->minionName,
                $this->level,
                $this->enemyTypeName,
                $this->combatPositionName,
                $this->attackNames,
                []
            );
        } catch (\Exception $exception) {
            $minion = Minion::query()->where('name', '', $this->minionName)->first();
            $this->assertNull($minion);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_a_chest_blueprint_is_not_found()
    {
        $chestBlueprintArrays = $this->chestBlueprintSeedArrays;
        $chestBlueprintArrays[] = [
            'chest_blueprint_id' => Str::random(10),
            'count' => 1,
            'chance' => 100
        ];
        try {
            $this->getDomainAction()->execute(
                $this->minionName,
                $this->level,
                $this->enemyTypeName,
                $this->combatPositionName,
                $this->attackNames,
                $chestBlueprintArrays
            );
        } catch (\Exception $exception) {
            $minion = Minion::query()->where('name', '', $this->minionName)->first();
            $this->assertNull($minion);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_create_a_minion()
    {
        $minion = $this->getDomainAction()->execute(
            $this->minionName,
            $this->level,
            $this->enemyTypeName,
            $this->combatPositionName,
            $this->attackNames,
            $this->chestBlueprintSeedArrays
        );

        $this->assertEquals($this->minionName, $minion->name);
        $this->assertEquals($this->level, $minion->level);
        $this->assertEquals($this->enemyTypeName, $minion->enemyType->name);
        $this->assertEquals($this->combatPositionName, $minion->combatPosition->name);
    }

    /**
     * @test
     */
    public function it_will_attach_attacks_to_the_minion()
    {
        $minion = $this->getDomainAction()->execute(
            $this->minionName,
            $this->level,
            $this->enemyTypeName,
            $this->combatPositionName,
            $this->attackNames,
            $this->chestBlueprintSeedArrays
        );

        $attacks = $minion->attacks;
        $this->assertEquals(count($this->attackNames), $attacks->count());
        $attacks->each(function (Attack $attack) {
            $this->assertTrue(in_array($attack->name, $this->attackNames), $attack->name . " not found");
        });
    }

    /**
     * @test
     */
    public function it_will_attach_the_chest_blueprints_to_the_minion_with_correct_pivot_values()
    {
        $minion = $this->getDomainAction()->execute(
            $this->minionName,
            $this->level,
            $this->enemyTypeName,
            $this->combatPositionName,
            $this->attackNames,
            $this->chestBlueprintSeedArrays
        );

        $chestBlueprints = $minion->chestBlueprints;
        $this->assertEquals(count($this->chestBlueprintSeedArrays), $chestBlueprints->count());
        $chestBlueprints->each(function (ChestBlueprint $chestBlueprint) {
            $matchingSeedArray = collect($this->chestBlueprintSeedArrays)->first(function ($blueprintSeedArray) use ($chestBlueprint) {
                return $blueprintSeedArray['chest_blueprint'] === $chestBlueprint->reference_id;
            });
            $this->assertNotNull($matchingSeedArray);
            $this->assertEquals($matchingSeedArray['count'], $chestBlueprint->pivot->count);
            $this->assertEquals($matchingSeedArray['chance'], $chestBlueprint->pivot->chance);
        });
    }
}
