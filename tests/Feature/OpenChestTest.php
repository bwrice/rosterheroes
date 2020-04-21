<?php

namespace Tests\Feature;

use App\Domain\Actions\OpenChest;
use App\Factories\Models\ChestFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OpenChestTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return OpenChest
     */
    protected function getDomainAction()
    {
        return app(OpenChest::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_chest_is_already_opened()
    {
        $chest = ChestFactory::new()->opened()->create();

        $this->assertNotNull($chest->opened_at);

        try {
            $this->getDomainAction()->execute($chest);
        } catch (\Exception $exception) {
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_update_opened_at_on_chest()
    {
        $chest = ChestFactory::new()->create();
        $this->assertNull($chest->opened_at);

        $this->getDomainAction()->execute($chest);

        $this->assertNotNull($chest->fresh()->opened_at);
    }

    /**
     * @test
     */
    public function it_will_increase_the_gold_of_the_squad_by_the_gold_the_chest_contains()
    {
        $chest = ChestFactory::new()->create();
        $chestGold = $chest->gold;
        $this->assertGreaterThan(0, $chestGold);
        $squad = $chest->squad;
        $squadPreviousGold = $squad->gold;

        $this->getDomainAction()->execute($chest);

        $currentSquadGold = $squad->fresh()->gold;
        $this->assertEquals($chestGold + $squadPreviousGold, $currentSquadGold);
    }
}
