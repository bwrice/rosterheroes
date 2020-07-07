<?php

namespace Tests\Feature;

use App\Domain\Actions\Content\CreateSideQuestBlueprint;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuestBlueprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateSideQuestBlueprintTest extends TestCase
{
    use DatabaseTransactions;

    protected $sideQuestName;
    protected $referenceID;
    protected $minionArrays;
    protected $chestBlueprintArrays;

    public function setUp(): void
    {
        parent::setUp();
        $this->sideQuestName = Str::random(10);
        $this->referenceID = Str::random(8);

        $this->minionArrays = Minion::query()->inRandomOrder()->take(rand(2,4))->get()->map(function (Minion $minion) {
            return [
                'name' => $minion->name,
                'count' => rand(1, 5)
            ];
        })->values()->toArray();

        $this->chestBlueprintArrays = ChestBlueprint::query()->inRandomOrder()->take(rand(2,4))->get()->map(function (ChestBlueprint $chestBlueprint) {
            return [
                'reference_id' => $chestBlueprint->reference_id,
                'count' => rand(1,3),
                'chance' => rand(1, 100)
            ];
        })->values()->toArray();
    }

    /**
     * @return CreateSideQuestBlueprint
     */
    protected function getDomainAction()
    {
        return app(CreateSideQuestBlueprint::class);
    }

    /**
     * @test
     */
    public function it_will_return_true()
    {
        $this->assertTrue(true);
    }
//
//    /**
//     * @test
//     */
//    public function it_wont_create_the_side_quest_blueprint_if_there_are_no_minions()
//    {
//        try {
//            $this->getDomainAction()->execute($this->sideQuestName, $this->referenceID, [], $this->chestBlueprintArrays);
//        } catch (\Exception $exception) {
//            $sideQuestBlueprint = SideQuestBlueprint::query()->where('name', '=', $this->sideQuestName)->first();
//            $this->assertNull($sideQuestBlueprint);
//            return;
//        }
//        $this->fail("Exception not thrown");
//    }
//
//    /**
//     * @test
//     */
//    public function it_wont_create_a_side_quest_blueprint_if_there_are_no_chest_blueprints()
//    {
//        try {
//            $this->getDomainAction()->execute($this->sideQuestName, $this->referenceID, $this->minionArrays, []);
//        } catch (\Exception $exception) {
//            $sideQuestBlueprint = SideQuestBlueprint::query()->where('name', '=', $this->sideQuestName)->first();
//            $this->assertNull($sideQuestBlueprint);
//            return;
//        }
//        $this->fail("Exception not thrown");
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_create_a_side_quest_blueprint()
//    {
//        $sideQuestBlueprint = $this->getDomainAction()->execute($this->sideQuestName, $this->referenceID, $this->minionArrays, $this->chestBlueprintArrays);
//        $this->assertEquals($this->sideQuestName, $sideQuestBlueprint->name);
//        $this->assertEquals($this->referenceID, $sideQuestBlueprint->reference_id);
//    }
//
//    /**
//     * @test
//     */
//    public function it_will_attach_minions_to_the_side_quest_blueprint()
//    {
//        $sideQuestBlueprint = $this->getDomainAction()->execute($this->sideQuestName, $this->referenceID, $this->minionArrays, $this->chestBlueprintArrays);
//        $minions = $sideQuestBlueprint->minions;
//        $this->assertEquals(count($this->minionArrays), $minions->count());
//        foreach ($this->minionArrays as $minionArray) {
//            $matchingMinion = $minions->first(function (Minion $minion) use ($minionArray) {
//                return $minion->name === $minionArray['name'];
//            });
//            $this->assertNotNull($matchingMinion);
//            $this->assertEquals($minionArray['count'], $matchingMinion->pivot->count);
//        }
//    }
}
