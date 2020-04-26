<?php

namespace Tests\Feature;

use App\Domain\Actions\StashItemFromMobileStorage;
use App\Factories\Models\ItemFactory;
use App\Factories\Models\ResidenceFactory;
use App\Factories\Models\SquadFactory;
use App\Factories\Models\StashFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestsItemsTransactions;

class StashItemFromMobileStorageTest extends TestCase
{
    use TestsItemsTransactions;
    use DatabaseTransactions;

    /**
     * @return StashItemFromMobileStorage
     */
    protected function getDomainAction()
    {
        return app(StashItemFromMobileStorage::class);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_item_does_not_belong_to_the_squad()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $diffSquad = SquadFactory::new()->create();
        $diffSquad->items()->save($item);

        try {
            $this->getDomainAction()->execute($item, $squad);
        } catch (\Exception $exception) {
            $this->assertTrue($item->ownedByMorphable($diffSquad));
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_move_the_item_to_the_stash_at_the_squads_current_location()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $squad->items()->save($item);
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();

        $item = $this->getDomainAction()->execute($item, $squad);

        $this->assertTrue($item->ownedByMorphable($stash));
        $this->assertItemTransactionMatches($item, $stash, $squad);
    }

    /**
     * @test
     */
    public function it_will_stash_the_item_even_if_a_local_residence_with_space_exists()
    {
        $squad = SquadFactory::new()->create();
        $item = ItemFactory::new()->create();
        $squad->items()->save($item);
        $stash = StashFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $localResidence = ResidenceFactory::new()->withSquadID($squad->id)->atProvince($squad->province)->create();
        $residenceItemsCount = $localResidence->items()->count();

        $item = $this->getDomainAction()->execute($item, $squad);

        $this->assertTrue($item->ownedByMorphable($stash));
        $this->assertItemTransactionMatches($item, $stash, $squad);
        $this->assertEquals($residenceItemsCount, $localResidence->items()->count());
    }
}
