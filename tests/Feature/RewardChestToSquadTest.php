<?php

namespace Tests\Feature;

use App\Domain\Actions\RewardChestToSquad;
use App\Factories\Models\ChestBlueprintFactory;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RewardChestToSquadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_create_a_chest_for_the_squad_with_the_correct_gold()
    {
        $squad = SquadFactory::new()->create();
        $chestBlueprint = ChestBlueprintFactory::new()->create();

        /** @var RewardChestToSquad $domainAction */
        $domainAction = app(RewardChestToSquad::class);
        $chest = $domainAction->execute($chestBlueprint, $squad);

        $chestGold = $chest->gold;
        $this->assertGreaterThan($chestBlueprint->min_gold, $chestGold);
        $this->assertLessThan($chestBlueprint->max_gold, $chestGold);
        $this->assertEquals($squad->id, $chest->squad_id);
        $this->assertEquals($chestBlueprint->id, $chest->chest_blueprint_id);
    }
}
